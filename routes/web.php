<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\IngresoInsumoController;
use App\Http\Controllers\SalidaInsumoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\InsumoAgrupadoController;
use App\Http\Controllers\CuracionController;
use App\Http\Controllers\CuracionRecetaController;
use App\Http\Controllers\EstudioComplementarioController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\PagoMedicoController;
use App\Http\Controllers\LiquidacionMedicoController;


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
Route::resource('users', UserController::class);

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

Route::get('/consultations/{id}/receipt', [ConsultationController::class, 'receipt'])
    ->name('consultations.receipt');

Route::get('/doctors/{id}/payments', [DoctorController::class, 'payments'])
    ->name('doctors.payments');

// PAGAR DOCTOR
Route::get('/doctors/{id}/pay', [DoctorController::class, 'payDoctor'])
    ->name('doctors.pay');

// LISTADO DE RECIBOS
Route::get('/medical-receipts', [DoctorController::class, 'receiptsIndex'])
    ->name('medical_receipts.index');

// DETALLE DEL RECIBO
Route::get('/medical-receipts/{number}', [DoctorController::class, 'receiptDetail'])
    ->name('medical_receipts.show');
Route::get('/medical-receipts/{number}/pdf', [DoctorController::class, 'receiptPdf'])
    ->name('medical_receipts.pdf');

// ========================================
// INSUMOS
// ========================================

Route::middleware('auth')->group(function () {

    // LISTADO
    Route::get('/insumos', [InsumoController::class, 'index'])
        ->name('insumos.index');

    // CREAR
    Route::get('/insumos/crear', [InsumoController::class, 'create'])
        ->name('insumos.create');

    Route::post('/insumos', [InsumoController::class, 'store'])
        ->name('insumos.store');

    // EDITAR
    Route::get('/insumos/{id}/editar', [InsumoController::class, 'edit'])
        ->name('insumos.edit');

    Route::put('/insumos/{id}', [InsumoController::class, 'update'])
        ->name('insumos.update');

    // ELIMINAR
    Route::delete('/insumos/{id}', [InsumoController::class, 'destroy'])
        ->name('insumos.destroy');

    // INGRESOS
    Route::get('/insumos/ingresos', [IngresoInsumoController::class, 'index'])
        ->name('insumos.ingresos.index');

    Route::get('/insumos/ingresos/crear', [IngresoInsumoController::class, 'create'])
        ->name('insumos.ingresos.create');

    Route::post('/insumos/ingresos', [IngresoInsumoController::class, 'store'])
        ->name('insumos.ingresos.store');

    Route::get('/insumos/ingresos/{id}/pdf', [IngresoInsumoController::class, 'pdf'])->name('insumos.ingresos.pdf');

    // SALIDAS
    Route::get('/insumos/salidas', [SalidaInsumoController::class, 'index'])
        ->name('insumos.salidas.index');

    Route::get('/insumos/salidas/crear', [SalidaInsumoController::class, 'create'])
        ->name('insumos.salidas.create');

    Route::post('/insumos/salidas', [SalidaInsumoController::class, 'store'])
        ->name('insumos.salidas.store');

    Route::get('/insumos/salidas/{id}/pdf', [SalidaInsumoController::class, 'pdf'])
        ->name('insumos.salidas.pdf');

    Route::get('/insumos/salidas/{id}/recibo', [SalidaInsumoController::class, 'recibo'])
        ->name('insumos.salidas.recibo');

    // INVENTARIO
    Route::get('/insumos/inventario', [InventarioController::class, 'index'])
        ->name('insumos.inventario');

    // PDF
    Route::get('/insumos/inventario/pdf', [InventarioController::class, 'pdf'])
        ->name('insumos.inventario.pdf');

    // =====================================================
// INSUMOS AGRUPADOS
// =====================================================

    Route::get('/insumos/agrupados', [InsumoAgrupadoController::class, 'index'])
        ->name('insumos.agrupados.index');

    Route::get('/insumos/agrupados/create', [InsumoAgrupadoController::class, 'create'])
        ->name('insumos.agrupados.create');

    Route::post('/insumos/agrupados', [InsumoAgrupadoController::class, 'store'])
        ->name('insumos.agrupados.store');

    Route::get('/insumos/agrupados/{id}/edit', [InsumoAgrupadoController::class, 'edit'])
        ->name('insumos.agrupados.edit');

    Route::put('/insumos/agrupados/{id}', [InsumoAgrupadoController::class, 'update'])
        ->name('insumos.agrupados.update');

    Route::delete('/insumos/agrupados/{id}', [InsumoAgrupadoController::class, 'destroy'])
        ->name('insumos.agrupados.destroy');


    // =====================================================
// CURACIONES
// =====================================================

    Route::get('/curaciones', [CuracionController::class, 'index'])
        ->name('curaciones.index');

    Route::get('/curaciones/create', [CuracionController::class, 'create'])
        ->name('curaciones.create');

    Route::post('/curaciones', [CuracionController::class, 'store'])
        ->name('curaciones.store');

    Route::get('/curaciones/{id}', [CuracionController::class, 'show'])
        ->name('curaciones.show');

    Route::get('/curaciones/{id}/edit', [CuracionController::class, 'edit'])
        ->name('curaciones.edit');

    Route::put('/curaciones/{id}', [CuracionController::class, 'update'])
        ->name('curaciones.update');

    Route::delete('/curaciones/{id}', [CuracionController::class, 'destroy'])
        ->name('curaciones.destroy');


    // =====================================================
// PDF CURACIÓN
// =====================================================

    Route::get('/curaciones/{id}/pdf', [CuracionController::class, 'pdf'])
        ->name('curaciones.pdf');


    // =====================================================
// RECETAS
// =====================================================

    Route::get('/curaciones/{id}/receta/create', [CuracionRecetaController::class, 'create'])
        ->name('curaciones.receta.create');

    Route::post('/curaciones/{id}/receta', [CuracionRecetaController::class, 'store'])
        ->name('curaciones.receta.store');

    Route::get('/curaciones/{id}/receta/pdf', [CuracionRecetaController::class, 'pdf'])
        ->name('curaciones.receta.pdf');

    // ======================================================
// ESTUDIOS COMPLEMENTARIOS
// ======================================================

    Route::get('/estudios', [EstudioComplementarioController::class, 'index'])
        ->name('estudios.index');

    Route::get('/estudios/create', [EstudioComplementarioController::class, 'create'])
        ->name('estudios.create');

    Route::post('/estudios', [EstudioComplementarioController::class, 'store'])
        ->name('estudios.store');

    Route::get('/estudios/{id}', [EstudioComplementarioController::class, 'show'])
        ->name('estudios.show');

    Route::get('/estudios/{id}/edit', [EstudioComplementarioController::class, 'edit'])
        ->name('estudios.edit');

    Route::put('/estudios/{id}', [EstudioComplementarioController::class, 'update'])
        ->name('estudios.update');

    Route::delete('/estudios/{id}', [EstudioComplementarioController::class, 'destroy'])
        ->name('estudios.destroy');

    // PDF RECIBO PACIENTE
    Route::get('/estudios/{id}/pdf-paciente', [EstudioComplementarioController::class, 'pdfPaciente'])
        ->name('estudios.pdf.paciente');

    // PDF RECIBO LABORATORIO
    Route::get('/estudios/{id}/pdf-laboratorio', [EstudioComplementarioController::class, 'pdfLaboratorio'])
        ->name('estudios.pdf.laboratorio');


    Route::get('/cajas', [CajaController::class, 'index'])
        ->name('cajas.index');

    Route::get('/cajas/transferencia', [CajaController::class, 'formularioTransferencia'])
        ->name('cajas.transferencia');

    Route::post('/cajas/transferencia', [CajaController::class, 'transferir'])
        ->name('cajas.transferencia.store');

    Route::get('/cajas/{id}/movimientos', [CajaController::class, 'movimientos'])
        ->name('cajas.movimientos');

    // PAGOS MÉDICOS
    Route::get('/pagos-medicos/crear/{consultation_id}', [PagoMedicoController::class, 'create'])
        ->name('pagos_medicos.create');

    Route::post('/pagos-medicos', [PagoMedicoController::class, 'store'])
        ->name('pagos_medicos.store');

    Route::get('/pagos-medicos/{id}', [PagoMedicoController::class, 'show'])
        ->name('pagos_medicos.show');

    Route::get(
        '/consultations/{id}/receipt',
        [ConsultationController::class, 'receipt']
    )->name('consultations.receipt');

    Route::get(
        '/liquidaciones-medicos',
        [LiquidacionMedicoController::class, 'index']
    )->name('liquidaciones_medicos.index');

    Route::post(
        '/liquidaciones-medicos/consultar',
        [LiquidacionMedicoController::class, 'consultar']
    )->name('liquidaciones_medicos.consultar');

    Route::post(
        '/liquidaciones-medicos',
        [LiquidacionMedicoController::class, 'store']
    )->name('liquidaciones_medicos.store');

    Route::get(
        '/liquidaciones-medicos/{id}',
        [LiquidacionMedicoController::class, 'show']
    )->name('liquidaciones_medicos.show');

    Route::post(
        '/liquidaciones-medicos/{id}/pagar',
        [LiquidacionMedicoController::class, 'pagar']
    )->name('liquidaciones_medicos.pagar');

    Route::get(
        '/liquidaciones-medicos/{id}/pdf',
        [LiquidacionMedicoController::class, 'pdf']
    )->name('liquidaciones_medicos.pdf');


});



require __DIR__ . '/auth.php';
