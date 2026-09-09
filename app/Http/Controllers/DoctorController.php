<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MedicalReceipt;
use Barryvdh\DomPDF\Facade\Pdf;

class DoctorController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;

        $doctors = Doctor::withCount([
        ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombres', 'like', "%$search%")
                        ->orWhere('apellidos', 'like', "%$search%")
                        ->orWhere('ci', 'like', "%$search%");
                });
            })
            ->paginate(10);

        return view('doctors.index', compact('doctors', 'search'));
    }

    public function create()
    {
        return view('doctors.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'apellidos' => 'required|string|max:255',
            'nombres' => 'required|string|max:255',
            'ci' => 'required|string|max:255|unique:doctors,ci',
            'especialidad' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'ci.required' => 'El CI es obligatorio.',
            'ci.unique' => 'Este CI ya está registrado para otro doctor.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La fotografía debe ser JPG, JPEG o PNG.',
            'foto.max' => 'La fotografía no debe superar los 2 MB.',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('doctors', 'public');
        }

        Doctor::create($data);

        return redirect()
            ->route('doctors.index')
            ->with('success', 'Doctor registrado correctamente.');
    }



    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('doctors.edit', compact('doctor'));
    }


    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'apellidos' => 'required',
            'nombres' => 'required',
            'ci' => 'required',
            'especialidad' => 'required',
            'telefono' => 'required',
            'hora_inicio' => 'required|date_format:H:i,H:i:s',
            'hora_fin' => 'required|date_format:H:i,H:i:s',
            'duracion_cita' => 'required|integer|min:15',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // 🖼️ MANEJO DE FOTO
        if ($request->hasFile('foto')) {

            // eliminar foto anterior (opcional)
            if ($doctor->foto && Storage::exists('public/' . $doctor->foto)) {
                Storage::delete('public/' . $doctor->foto);
            }

            $ruta = $request->file('foto')->store('doctors', 'public');
            $doctor->foto = $ruta;
        }

        // 🔥 ACTUALIZAR DATOS
        $doctor->update([
            'apellidos' => $request->apellidos,
            'nombres' => $request->nombres,
            'ci' => $request->ci,
            'especialidad' => $request->especialidad,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'duracion_cita' => $request->duracion_cita,
        ]);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor actualizado correctamente');
    }
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()->route('doctors.index');
    }

   

   
    public function receiptsIndex()
    {
        $receipts = MedicalReceipt::with('doctor')
            ->select('receipt_number', 'doctor_id', 'total', 'created_at')
            ->groupBy('receipt_number', 'doctor_id', 'total', 'created_at')
            ->latest()
            ->get();

        return view('reports.medical_receipts', compact('receipts'));
    }

    public function receiptDetail($number)
    {
        $details = MedicalReceipt::with('patient')
            ->where('receipt_number', $number)
            ->get();

        return view('reports.medical_receipt_detail', compact('details'));
    }



    public function receiptPdf($number)
    {
        $details = MedicalReceipt::with(['patient', 'doctor'])
            ->where('receipt_number', $number)
            ->get();

        $pdf = Pdf::loadView('reports.medical_receipt_pdf', compact('details'));

        return $pdf->stream('recibo.pdf'); // 🔥 abre en nueva pestaña
    }
}
