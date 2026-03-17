<?php
namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{

    public function index(Request $request)
    {

        $search = $request->search;

        $doctors = Doctor::where('apellidos', 'like', "%$search%")
            ->orWhere('nombres', 'like', "%$search%")
            ->orWhere('ci', 'like', "%$search%")
            ->paginate(10);

        return view('doctors.index', compact('doctors', 'search'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('doctors', 'public');
        }

        Doctor::create($data);

        return redirect()->route('doctors.index');
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
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'duracion_cita' => 'required|integer|min:15',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // 🖼️ MANEJO DE FOTO
        if ($request->hasFile('foto')) {

            // eliminar foto anterior (opcional)
            if ($doctor->foto && \Storage::exists('public/' . $doctor->foto)) {
                \Storage::delete('public/' . $doctor->foto);
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

}