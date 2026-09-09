<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Caja;
use App\Models\PagoMedicoDistribucion;
use App\Models\CuracionDistribucion;
use App\Models\LiquidacionMedico;
use App\Models\LiquidacionMedicoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;

class LiquidacionMedicoController extends Controller
{
    /**
     * Pantalla principal de liquidaciones.
     */
    public function index()
    {
        $doctores = Doctor::orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

        $liquidaciones = LiquidacionMedico::with([
            'doctor'
        ])
            ->orderByDesc('id')
            ->get();

        return view(
            'liquidaciones_medicos.index',
            compact(
                'doctores',
                'liquidaciones'
            )
        );
    }

    /**
     * Consulta las atenciones médicas y curaciones
     * pendientes de liquidar.
     */
    public function consultar(Request $request)
    {
        $request->validate([
            'doctor_id' => [
                'required',
                'exists:doctors,id',
            ],
            'fecha_desde' => [
                'required',
                'date',
            ],
            'fecha_hasta' => [
                'required',
                'date',
                'after_or_equal:fecha_desde',
            ],
        ]);

        $doctorId = $request->doctor_id;
        $fechaDesde = $request->fecha_desde;
        $fechaHasta = $request->fecha_hasta;

        /*
        |--------------------------------------------------------------------------
        | OBTENER DETALLES YA LIQUIDADOS
        |--------------------------------------------------------------------------
        */

        $origenesLiquidadosPago = LiquidacionMedicoDetalle::where(
            'tipo_origen',
            'pago_medico'
        )
            ->whereHas('liquidacion', function ($query) {
                $query->where('estado', '!=', 'anulada');
            })
            ->pluck('origen_id')
            ->toArray();

        $origenesLiquidadosCuracion = LiquidacionMedicoDetalle::where(
            'tipo_origen',
            'curacion'
        )
            ->whereHas('liquidacion', function ($query) {
                $query->where('estado', '!=', 'anulada');
            })
            ->pluck('origen_id')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | ATENCIONES MÉDICAS
        |--------------------------------------------------------------------------
        */

        $atenciones = PagoMedicoDistribucion::with([
            'pagoMedico.consultation.appointment.patient',
            'doctor',
        ])
            ->where('doctor_id', $doctorId)
            ->where('concepto', 'medico')
            ->whereHas('pagoMedico', function ($query) use ($fechaDesde, $fechaHasta) {
                $query->whereBetween(
                    'fecha_atencion',
                    [$fechaDesde, $fechaHasta]
                )
                    ->where('estado', '!=', 'anulado');
            })
            ->when(
                count($origenesLiquidadosPago) > 0,
                function ($query) use ($origenesLiquidadosPago) {
                    $query->whereNotIn(
                        'id',
                        $origenesLiquidadosPago
                    );
                }
            )
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | CURACIONES
        |--------------------------------------------------------------------------
        */

        $curaciones = CuracionDistribucion::with([
            'curacion.consultation.appointment.patient',
            'doctor',
        ])
            ->where('doctor_id', $doctorId)
            ->whereHas('curacion', function ($query) use ($fechaDesde, $fechaHasta) {
                $query->whereBetween(
                    'fecha',
                    [$fechaDesde, $fechaHasta]
                )
                    ->where('estado', true);
            })
            ->when(
                count($origenesLiquidadosCuracion) > 0,
                function ($query) use ($origenesLiquidadosCuracion) {
                    $query->whereNotIn(
                        'id',
                        $origenesLiquidadosCuracion
                    );
                }
            )
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TOTALES
        |--------------------------------------------------------------------------
        */

        $totalAtenciones = $atenciones->sum('monto');

        $totalCuraciones = $curaciones->sum('monto');

        $totalGeneral = $totalAtenciones + $totalCuraciones;

        $doctor = Doctor::findOrFail($doctorId);

        return view(
            'liquidaciones_medicos.resultado',
            compact(
                'doctor',
                'fechaDesde',
                'fechaHasta',
                'atenciones',
                'curaciones',
                'totalAtenciones',
                'totalCuraciones',
                'totalGeneral'
            )
        );
    }

    /**
     * Genera una nueva liquidación.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => [
                'required',
                'exists:doctors,id',
            ],
            'fecha_desde' => [
                'required',
                'date',
            ],
            'fecha_hasta' => [
                'required',
                'date',
                'after_or_equal:fecha_desde',
            ],
        ]);

        try {

            DB::beginTransaction();

            $doctorId = $request->doctor_id;
            $fechaDesde = $request->fecha_desde;
            $fechaHasta = $request->fecha_hasta;

            /*
            |--------------------------------------------------------------------------
            | BUSCAR ORÍGENES YA UTILIZADOS
            |--------------------------------------------------------------------------
            */

            $origenesPago = LiquidacionMedicoDetalle::where(
                'tipo_origen',
                'pago_medico'
            )
                ->whereHas('liquidacion', function ($query) {
                    $query->where('estado', '!=', 'anulada');
                })
                ->pluck('origen_id')
                ->toArray();

            $origenesCuracion = LiquidacionMedicoDetalle::where(
                'tipo_origen',
                'curacion'
            )
                ->whereHas('liquidacion', function ($query) {
                    $query->where('estado', '!=', 'anulada');
                })
                ->pluck('origen_id')
                ->toArray();

            /*
            |--------------------------------------------------------------------------
            | OBTENER ATENCIONES
            |--------------------------------------------------------------------------
            */

            $atenciones = PagoMedicoDistribucion::with('pagoMedico')
                ->where('doctor_id', $doctorId)
                ->where('concepto', 'medico')
                ->whereHas('pagoMedico', function ($query) use ($fechaDesde, $fechaHasta) {
                    $query->whereBetween(
                        'fecha_atencion',
                        [$fechaDesde, $fechaHasta]
                    )
                        ->where('estado', '!=', 'anulado');
                })
                ->when(
                    count($origenesPago) > 0,
                    function ($query) use ($origenesPago) {
                        $query->whereNotIn(
                            'id',
                            $origenesPago
                        );
                    }
                )
                ->lockForUpdate()
                ->get();

            /*
            |--------------------------------------------------------------------------
            | OBTENER CURACIONES
            |--------------------------------------------------------------------------
            */

            $curaciones = CuracionDistribucion::with('curacion')
                ->where('doctor_id', $doctorId)
                ->whereHas('curacion', function ($query) use ($fechaDesde, $fechaHasta) {
                    $query->whereBetween(
                        'fecha',
                        [$fechaDesde, $fechaHasta]
                    )
                        ->where('estado', true);
                })
                ->when(
                    count($origenesCuracion) > 0,
                    function ($query) use ($origenesCuracion) {
                        $query->whereNotIn(
                            'id',
                            $origenesCuracion
                        );
                    }
                )
                ->lockForUpdate()
                ->get();

            $totalAtenciones = $atenciones->sum('monto');

            $totalCuraciones = $curaciones->sum('monto');

            $total = $totalAtenciones + $totalCuraciones;

            if ($total <= 0) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->with('error', 'No existen importes pendientes de liquidar para este médico en el rango seleccionado.');
            }

            /*
            |--------------------------------------------------------------------------
            | GENERAR NÚMERO
            |--------------------------------------------------------------------------
            */

            $ultimo = LiquidacionMedico::orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $numero = $ultimo
                ? $ultimo->id + 1
                : 1;

            $numero = 'LIQ-' . str_pad(
                $numero,
                6,
                '0',
                STR_PAD_LEFT
            );

            /*
            |--------------------------------------------------------------------------
            | CREAR LIQUIDACIÓN
            |--------------------------------------------------------------------------
            */

            $liquidacion = LiquidacionMedico::create([
                'numero' => $numero,
                'doctor_id' => $doctorId,
                'fecha' => now()->toDateString(),
                'total_generado' => $total,
                'total_pagado' => 0,
                'saldo' => $total,
                'estado' => 'pendiente',
                'usuario_id' => Auth::id(),
                'observacion' => "Periodo del {$fechaDesde} al {$fechaHasta}",
            ]);

            /*
            |--------------------------------------------------------------------------
            | DETALLES DE ATENCIONES
            |--------------------------------------------------------------------------
            */

            foreach ($atenciones as $atencion) {

                $fecha = $atencion->pagoMedico->fecha_atencion;

                LiquidacionMedicoDetalle::create([
                    'liquidacion_medico_id' => $liquidacion->id,
                    'tipo_origen' => 'pago_medico',
                    'origen_id' => $atencion->id,
                    'concepto' => 'Atención médica',
                    'fecha' => $fecha,
                    'monto' => $atencion->monto,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | DETALLES DE CURACIONES
            |--------------------------------------------------------------------------
            */

            foreach ($curaciones as $curacion) {

                LiquidacionMedicoDetalle::create([
                    'liquidacion_medico_id' => $liquidacion->id,
                    'tipo_origen' => 'curacion',
                    'origen_id' => $curacion->id,
                    'concepto' => $curacion->concepto,
                    'fecha' => $curacion->curacion->fecha,
                    'monto' => $curacion->monto,
                ]);
            }

            DB::commit();

            return redirect()
                ->route(
                    'liquidaciones_medicos.show',
                    $liquidacion->id
                )
                ->with(
                    'success',
                    'La liquidación médica fue generada correctamente.'
                );

        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'No fue posible generar la liquidación médica.'
                );
        }
    }

    /**
     * Mostrar una liquidación.
     */
    public function show($id)
    {
        $liquidacion = LiquidacionMedico::with([
            'doctor',
            'usuario',
            'detalles',
        ])->findOrFail($id);

        return view(
            'liquidaciones_medicos.show',
            compact('liquidacion')
        );
    }


    public function pagar($id)
    {
        try {

            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | BUSCAR Y BLOQUEAR LA LIQUIDACIÓN
            |--------------------------------------------------------------------------
            */

            $liquidacion = LiquidacionMedico::lockForUpdate()
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | VALIDAR ESTADO
            |--------------------------------------------------------------------------
            */

            if ($liquidacion->estado === 'pagada') {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Esta liquidación ya fue pagada.'
                    );
            }

            if ($liquidacion->estado === 'anulada') {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'No se puede pagar una liquidación anulada.'
                    );
            }

            if ($liquidacion->saldo <= 0) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'La liquidación no tiene saldo pendiente.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | BUSCAR CAJA DOCTORES
            |--------------------------------------------------------------------------
            */

            $caja = Caja::where('nombre', 'Doctores')
                ->where('estado', true)
                ->lockForUpdate()
                ->first();

            if (!$caja) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'No existe una caja activa llamada "Doctores".'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | MONTO A PAGAR
            |--------------------------------------------------------------------------
            */

            $monto = (float) $liquidacion->saldo;

            $saldoAnterior = (float) $caja->saldo;

            /*
            |--------------------------------------------------------------------------
            | VERIFICAR SALDO DE LA CAJA
            |--------------------------------------------------------------------------
            */

            if ($saldoAnterior < $monto) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Saldo insuficiente. La Caja Doctores tiene Bs. '
                        . number_format($saldoAnterior, 2)
                        . ' y necesitas Bs. '
                        . number_format($monto, 2)
                        . '.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | NUEVO SALDO DE CAJA
            |--------------------------------------------------------------------------
            */

            $saldoNuevo = $saldoAnterior - $monto;

            $caja->update([
                'saldo' => $saldoNuevo,
            ]);

            /*
            |--------------------------------------------------------------------------
            | REGISTRAR MOVIMIENTO DE CAJA
            |--------------------------------------------------------------------------
            */

            \App\Models\MovimientoCaja::create([

                'caja_id' => $caja->id,

                'tipo' => 'egreso',

                'concepto' => 'Pago de liquidación médica',

                'monto' => $monto,

                'saldo_anterior' => $saldoAnterior,

                'saldo_nuevo' => $saldoNuevo,

                'referencia_tipo' => 'liquidacion_medico',

                'referencia_id' => $liquidacion->id,

                'usuario_id' => Auth::id(),

                'fecha' => now(),

                'observacion' =>
                    'Pago de liquidación '
                    . $liquidacion->numero
                    . ' al médico '
                    . $liquidacion->doctor->apellidos
                    . ' '
                    . $liquidacion->doctor->nombres,
            ]);

            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR LIQUIDACIÓN
            |--------------------------------------------------------------------------
            */

            $liquidacion->update([

                'total_pagado' => $liquidacion->total_pagado + $monto,

                'saldo' => 0,

                'estado' => 'pagada',

            ]);

            /*
            |--------------------------------------------------------------------------
            | CONFIRMAR TRANSACCIÓN
            |--------------------------------------------------------------------------
            */

            DB::commit();

            return redirect()
                ->route(
                    'liquidaciones_medicos.show',
                    $liquidacion->id
                )
                ->with(
                    'success',
                    'La liquidación '
                    . $liquidacion->numero
                    . ' fue pagada correctamente.'
                );

        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'No fue posible realizar el pago de la liquidación.'
                );
        }
    }
}