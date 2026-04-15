<?php

namespace App\Http\Controllers;

use App\Models\MedicalPayment;
use App\Models\Consultation;
use Illuminate\Http\Request;

class MedicalPaymentController extends Controller
{
    public function index($id)
    {
        $consultation = Consultation::with('medicalPayments', 'appointment.doctor')
            ->findOrFail($id);

        return view('consultations.pago_medico', compact('consultation'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cost' => 'required|numeric|min:0',
            'percentage_newton' => 'required|numeric|min:0|max:100',
            'percentage_doctor' => 'required|numeric|min:0|max:100',
            'doctor_id' => 'required|exists:doctors,id'
        ]);

        if (($request->percentage_newton + $request->percentage_doctor) != 100) {
            return back()->with('error', 'Los porcentajes deben sumar 100%');
        }

        $cost = $request->cost;

        $cost_newton = ($cost * $request->percentage_newton) / 100;
        $cost_doctor = ($cost * $request->percentage_doctor) / 100;

        MedicalPayment::create([
            'consultation_id' => $request->consultation_id,
            'doctor_id' => $request->doctor_id,
            'cost' => $cost,
            'percentage_newton' => $request->percentage_newton,
            'percentage_doctor' => $request->percentage_doctor,
            'cost_newton' => $cost_newton,
            'cost_doctor' => $cost_doctor,
        ]);

        return back()->with('success', 'Pago médico registrado');
    }
}
