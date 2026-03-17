<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class PatientController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;

        $patients = Patient::where('nombres', 'like', "%$search%")
            ->orWhere('apellidos', 'like', "%$search%")
            ->orWhere('ci', 'like', "%$search%")
            ->paginate(10);

        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {

        $data = $request->all();

        if ($request->hasFile('fotografia')) {
            $data['fotografia'] = $request->file('fotografia')->store('patients', 'public');
        }

        Patient::create($data);

        return redirect()->route('patients.index')->with('success', 'Paciente registrado');
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {

        $data = $request->all();

        if ($request->hasFile('fotografia')) {
            $data['fotografia'] = $request->file('fotografia')->store('patients', 'public');
        }

        $patient->update($data);

        return redirect()->route('patients.index')->with('success', 'Paciente actualizado');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Paciente eliminado');
    }

    public function historial($id)
    {
        $patient = Patient::with([
            'appointments.doctor',
            'appointments.consultation'
        ])->findOrFail($id);

        return view('patients.historial', compact('patient'));
    }


    public function historialPdf($id)
    {
        $patient = Patient::with([
            'appointments.doctor',
            'appointments.consultation'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('patients.historial_pdf', compact('patient'));

        return $pdf->download('historial_clinico.pdf');
    }
}