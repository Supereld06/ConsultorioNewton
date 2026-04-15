<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\Consultation;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    // Vista
    public function index($consultation_id)
    {
        $consultation = Consultation::with('supplies')->findOrFail($consultation_id);

        return view('consultations.supplies', compact('consultation'));
    }

    // Guardar
public function store(Request $request)
{
    // 🔥 VALIDACIONES
    $request->validate([
        'name' => 'required|string|max:255',
        'cost' => 'required|numeric|min:0',
        'percentage_newton' => 'required|numeric|min:0|max:100',
        'percentage_clinic' => 'required|numeric|min:0|max:100',
    ]);

    // 🔥 VALIDACIÓN EXTRA (QUE SUMEN 100)
    if (($request->percentage_newton + $request->percentage_clinic) != 100) {
        return back()->with('error', 'Los porcentajes deben sumar 100%');
    }

    $cost = $request->cost;

    $cost_newton = ($cost * $request->percentage_newton) / 100;
    $cost_clinic = ($cost * $request->percentage_clinic) / 100;

    // 🔥 GUARDADO
    Supply::create([
        'consultation_id' => $request->consultation_id,
        'name' => strtoupper($request->name),
        'cost' => $cost,
        'percentage_newton' => $request->percentage_newton,
        'percentage_clinic' => $request->percentage_clinic,
        'cost_newton' => $cost_newton,
        'cost_clinic' => $cost_clinic,
    ]);

    return back()->with('success', 'Insumo registrado correctamente');
}
}
