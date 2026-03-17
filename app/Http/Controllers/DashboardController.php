<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $patients = Patient::count();
        $doctors = Doctor::count();

        $appointmentsToday = Appointment::whereDate('fecha', $today)->count();

        $consultationsToday = Consultation::whereDate('created_at', $today)
            ->where('atendido', true)
            ->count();

        $appointmentsList = Appointment::with('patient', 'doctor', 'consultation')
            ->whereDate('fecha', $today)
            ->orderBy('hora')
            ->get();

        return view('dashboard', compact(
            'patients',
            'doctors',
            'appointmentsToday',
            'consultationsToday',
            'appointmentsList'
        ));
    }
}