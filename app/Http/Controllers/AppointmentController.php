<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();

        return view('appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'fecha' => 'required|date',
            'hora' => 'required'
        ]);

        // 🚫 VALIDAR SI EL DOCTOR YA TIENE CITA
        $existe = Appointment::where('doctor_id', $request->doctor_id)
            ->where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->where('estado', '!=', 'cancelado')
            ->exists();

        if ($existe) {
            return back()->with('error', 'El doctor ya tiene una cita en ese horario');
        }

        Appointment::create($request->all());

        return redirect()->route('appointments.index')
            ->with('success', 'Cita registrada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();

        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'fecha' => 'required',
            'hora' => 'required'
        ]);

        // 🚫 VALIDAR CHOQUE (EXCLUYENDO LA MISMA CITA)
        $existe = Appointment::where('doctor_id', $request->doctor_id)
            ->where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->where('id', '!=', $appointment->id)
            ->where('estado', '!=', 'cancelado')
            ->exists();

        if ($existe) {
            return back()->with('error', 'El doctor ya tiene una cita en ese horario');
        }

        $appointment->update($request->all());

        return redirect()->route('appointments.index')
            ->with('success', 'Cita actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }

    public function horariosDisponibles(Request $request)
    {
        $doctor = \App\Models\Doctor::findOrFail($request->doctor_id);
        $fecha = $request->fecha;

        $inicio = \Carbon\Carbon::createFromFormat('H:i:s', $doctor->hora_inicio);
        $fin = \Carbon\Carbon::createFromFormat('H:i:s', $doctor->hora_fin);

        $intervalo = $doctor->duracion_cita;

        $horas = [];

        // 🔴 HORAS OCUPADAS
        $ocupadas = \App\Models\Appointment::where('doctor_id', $doctor->id)
            ->where('fecha', $fecha)
            ->where('estado', '!=', 'cancelado')
            ->pluck('hora')
            ->map(fn($h) => substr($h, 0, 5))
            ->toArray();

        while ($inicio < $fin) {

            $hora = $inicio->format('H:i');

            $horas[] = [
                'hora' => $hora,
                'ocupado' => in_array($hora, $ocupadas)
            ];

            $inicio->addMinutes($intervalo);
        }

        return response()->json($horas);
    }
}
