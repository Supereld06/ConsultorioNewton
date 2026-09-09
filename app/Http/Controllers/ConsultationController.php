<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $consultation = Consultation::firstOrCreate([
            'appointment_id' => $appointment->id
        ]);

        return view('consultations.edit', compact('consultation', 'appointment'));
    }

    public function update(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

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

    public function pdf($id)
    {
        $consultation = Consultation::with('appointment.patient', 'appointment.doctor')
            ->findOrFail($id);

        $pdf = Pdf::loadView('consultations.pdf', compact('consultation'))
            ->setPaper([0, 0, 340, 520]);

        return $pdf->stream('consulta.pdf');
    }

    public function receipt($id)
    {
        $consultation = Consultation::with([
            'appointment.patient',
            'appointment.doctor',
            'pagoMedico'
        ])->findOrFail($id);

        // Verificar que exista un pago médico
        if (!$consultation->pagoMedico) {
            return redirect()
                ->back()
                ->with('error', 'Esta consulta todavía no tiene un pago médico registrado.');
        }

        // Generar número de recibo si todavía no existe
        if (!$consultation->receipt_number) {

            $consultation->receipt_number =
                'REC-' . str_pad($consultation->id, 6, '0', STR_PAD_LEFT);

            $consultation->save();
        }

        $pdf = Pdf::loadView('consultations.receipt', [
            'consultation' => $consultation,
            'pagoMedico' => $consultation->pagoMedico,
        ]);

        return $pdf->stream(
            'recibo-atencion-medica-' . $consultation->receipt_number . '.pdf'
        );
    }


}