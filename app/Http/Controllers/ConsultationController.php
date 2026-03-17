<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// ✅ IMPORTACIONES CORRECTAS
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Consultation;
use Barryvdh\DomPDF\Facade\Pdf;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $doctor_id = $request->doctor_id;

        $appointments = Appointment::with(['patient', 'doctor', 'consultation'])
            ->when($doctor_id, function ($q) use ($doctor_id) {
                $q->where('doctor_id', $doctor_id);
            })
            ->orderBy('fecha', 'desc')
            ->paginate(10);

        $doctors = Doctor::all();

        return view('consultations.index', compact('appointments', 'doctors'));
    }

    public function atender($id)
    {
        $appointment = Appointment::with('patient', 'doctor', 'consultation')
            ->findOrFail($id);

        // 🔥 crear si no existe
        $consultation = Consultation::firstOrCreate([
            'appointment_id' => $appointment->id
        ]);

        return view('consultations.edit', compact('consultation', 'appointment'));
    }

    public function update(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        $request->validate([
            'diagnostico' => 'required'
        ]);

        $consultation->update([
            'diagnostico' => $request->diagnostico,
            'tratamiento' => $request->tratamiento,
            'receta' => $request->receta,
            'observaciones' => $request->observaciones,
            'atendido' => true
        ]);

        return redirect()->route('consultations.index')
            ->with('success', 'Consulta atendida correctamente');
    }

    // ✅ PDF CORREGIDO
    public function pdf($id)
    {
        $consultation = Consultation::with('appointment.patient', 'appointment.doctor')
            ->findOrFail($id);

        $pdf = Pdf::loadView('consultations.pdf', compact('consultation'));

        return $pdf->download('consulta.pdf');
    }
}