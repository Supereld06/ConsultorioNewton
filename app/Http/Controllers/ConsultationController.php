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
            'motivo_consulta' => $request->motivo_consulta,
            'cuadro_clinico' => $request->cuadro_clinico,
            'diagnostico' => $request->diagnostico,
            'estudios' => $request->estudios,
            'receta' => $request->receta,
            'tratamiento' => $request->tratamiento,
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

        $pdf = Pdf::loadView('consultations.pdf', compact('consultation'))
            ->setPaper([0, 0, 340, 520]); // 📄 Media carta

        return $pdf->stream('consulta.pdf'); // 👈 abre en pestaña
    }

    // ✅ NUEVO MÉTODO PARA RECIBO
    public function receipt($id)
    {
        $consultation = Consultation::with([
            'appointment.patient',
            'appointment.doctor',
            'supplies',
            'medicalPayments'
        ])->findOrFail($id);

        // 🔥 AQUÍ VA
        if (!$consultation->receipt_number) {
            $consultation->receipt_number = 'REC-' . str_pad($consultation->id, 6, '0', STR_PAD_LEFT);
            $consultation->save();
        }

        // 🔥 TOTALES
        $totalSupplies = $consultation->supplies->sum('cost');
        $totalMedical = $consultation->medicalPayments->sum('cost');

        $issueDate = now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('consultations.receipt', compact(
            'consultation',
            'totalSupplies',
            'totalMedical',
            'issueDate'
        ))->setPaper([0, 0, 340, 482]);

        return $pdf->stream('recibo.pdf');
    }
}