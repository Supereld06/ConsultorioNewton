<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\MedicalPaymentController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas para pacientes
Route::resource('patients', PatientController::class);
Route::get('/patients/{id}/historial', [PatientController::class, 'historial'])
    ->name('patients.historial');
Route::get('/patients/{id}/historial-pdf', [PatientController::class, 'historialPdf'])
    ->name('patients.historial.pdf');

// Rutas para doctores
Route::resource('doctors', DoctorController::class);

// Rutas para citas
Route::resource('appointments', AppointmentController::class);

Route::get('/horarios-disponibles', [AppointmentController::class, 'horariosDisponibles']);

//rutas consultas
Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
Route::get('/consultations/atender/{id}', [ConsultationController::class, 'atender'])->name('consultations.atender');
Route::put('/consultations/{id}', [ConsultationController::class, 'update'])->name('consultations.update');
Route::get('/consultations/pdf/{id}', [ConsultationController::class, 'pdf'])->name('consultations.pdf');
Route::get('/consultations/{id}', function ($id) {
    return \App\Models\Consultation::findOrFail($id);
});

Route::get('/supplies/{id}', [SupplyController::class, 'index'])->name('supplies.index');
Route::post('/supplies', [SupplyController::class, 'store'])->name('supplies.store');

Route::get('/medical-payments/{id}', [MedicalPaymentController::class, 'index'])->name('medical_payments.index');
Route::post('/medical-payments', [MedicalPaymentController::class, 'store'])->name('medical_payments.store');

Route::get('/consultations/{id}/receipt', [ConsultationController::class, 'receipt'])
    ->name('consultations.receipt');

require __DIR__.'/auth.php';
