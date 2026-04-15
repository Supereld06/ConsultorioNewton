<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MedicalPayment;
use App\Models\MedicalReceipt;
use Barryvdh\DomPDF\Facade\Pdf;

class DoctorController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;

        $doctors = Doctor::withCount([
            'medicalPayments as pending_payments' => function ($query) {
                $query->where('paid', 0);
            }
        ])->paginate(10);

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

    public function payments($id)
    {
        $doctor = Doctor::findOrFail($id);

        // 🔥 SOLO PAGOS NO PAGADOS
        $payments = MedicalPayment::with([
            'consultation.appointment.patient'
        ])
            ->where('doctor_id', $id)
            ->where('paid', false)
            ->get();

        // 🔥 TOTAL A PAGAR AL DOCTOR
        $totalDoctor = $payments->sum('cost_doctor');

        return view('doctors.payments', compact('doctor', 'payments', 'totalDoctor'));
    }

    public function payDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);

        $payments = MedicalPayment::with('consultation.appointment.patient')
            ->where('doctor_id', $id)
            ->where('paid', false)
            ->get();

        if ($payments->isEmpty()) {
            return back()->with('error', 'No hay pagos pendientes');
        }

        // 🔥 TOTAL
        $total = $payments->sum('cost_doctor');

        // 🔥 👇 AQUÍ VA EL CÓDIGO DEL NÚMERO DE RECIBO
        $lastReceipt = MedicalReceipt::select('receipt_number')
            ->groupBy('receipt_number')
            ->orderBy('receipt_number', 'desc')
            ->first();

        if ($lastReceipt) {
            $lastNumber = (int) substr($lastReceipt->receipt_number, -2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $receiptNumber = 'Rec-Med-' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        // 🔥 👆 FIN

        // 🔥 GUARDAR
        foreach ($payments as $p) {

            $patient = $p->consultation->appointment->patient;

            MedicalReceipt::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'receipt_number' => $receiptNumber,
                'date' => $p->consultation->appointment->fecha,
                'time' => $p->consultation->appointment->hora,
                'cost_medico' => $p->cost_doctor,
                'total' => $total
            ]);
        }

        // 🔥 MARCAR PAGADOS
        MedicalPayment::where('doctor_id', $id)
            ->where('paid', false)
            ->update(['paid' => true]);

        return redirect()->route('medical_receipts.index')
            ->with('success', 'Pago realizado correctamente');
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
