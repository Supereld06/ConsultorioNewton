<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Consultation;
use App\Models\Appointment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // =====================================================
        // CONTADORES
        // =====================================================

        $patients = Patient::count();

        $doctors = Doctor::count();

        $appointmentsToday = Appointment::whereDate('fecha', $today)
            ->count();

        $consultationsToday = Consultation::whereDate('created_at', $today)
            ->where('atendido', true)
            ->count();


        // =====================================================
        // TODAS LAS CITAS
        // 12 POR PÁGINA
        // =====================================================

        $appointmentsList = Appointment::with([
            'patient',
            'doctor',
            'consultation.curacion',
            'consultation.estudiosComplementarios',
            'consultation.pagoMedico',
        ])
            ->orderByDesc('fecha')
            ->orderBy('hora')
            ->paginate(12)
            ->withQueryString();


        return view('dashboard', compact(
            'patients',
            'doctors',
            'appointmentsToday',
            'consultationsToday',
            'appointmentsList'
        ));
    }
}