<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Consultation;
use App\Models\Curacion;
use App\Models\CuracionDetalle;
use App\Models\CuracionDistribucion;
use App\Models\Insumo;
use App\Models\InsumoAgrupado;
use App\Models\MovimientoCaja;
use App\Models\SalidaInsumo;
use App\Models\SalidaInsumoDetalle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;

class CuracionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $curaciones = Curacion::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor'
        ])
            ->orderByDesc('id')
            ->paginate(10);

        return view('curaciones.index', compact('curaciones'));
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO NUEVA CURACIÓN
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $consultations = Consultation::with([
            'appointment.patient',
            'appointment.doctor'
        ])
            ->where('atendido', true)
            ->whereDoesntHave('curacion')
            ->orderByDesc('id')
            ->get();

        $insumos = Insumo::where('estado', true)
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        $agrupados = InsumoAgrupado::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('curaciones.create', compact(
            'consultations',
            'insumos',
            'agrupados'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR CURACIÓN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',

            'fecha' => 'required|date',

            'descripcion' => 'nullable|string',

            'costo_curacion' => 'required|numeric|min:0',

            'detalles' => 'required|array|min:1',

            'detalles.*.tipo' => [
                'required',
                'in:insumo,agrupado,otro'
            ],

            'detalles.*.cantidad' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'detalles.*.precio_unitario' => [
                'required',
                'numeric',
                'min:0'
            ],

            'porcentaje_doctor' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],

            'porcentaje_otros' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],

            'porcentaje_empresa' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | PORCENTAJES
        |--------------------------------------------------------------------------
        */

        $porcentajeDoctor = round(
            (float) $request->porcentaje_doctor,
            2
        );

        $porcentajeOtros = round(
            (float) $request->porcentaje_otros,
            2
        );

        $porcentajeEmpresa = round(
            (float) $request->porcentaje_empresa,
            2
        );


        $sumaPorcentajes = round(
            $porcentajeDoctor
            + $porcentajeOtros
            + $porcentajeEmpresa,
            2
        );


        if ($sumaPorcentajes !== 100.00) {
            return back()
                ->withInput()
                ->withErrors([
                    'porcentaje_doctor' =>
                        'La distribución debe sumar exactamente 100%.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CONSULTA
        |--------------------------------------------------------------------------
        */

        $consultation = Consultation::with([
            'appointment.patient',
            'appointment.doctor'
        ])->findOrFail(
                $request->consultation_id
            );


        $doctorId = optional(
            $consultation->appointment
        )->doctor_id;


        /*
        |--------------------------------------------------------------------------
        | SI EXISTE PORCENTAJE PARA DOCTOR,
        | DEBE EXISTIR UN DOCTOR
        |--------------------------------------------------------------------------
        */

        if ($porcentajeDoctor > 0 && !$doctorId) {
            return back()
                ->withInput()
                ->withErrors([
                    'porcentaje_doctor' =>
                        'La consulta seleccionada no tiene un doctor asignado.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | TRANSACCIÓN COMPLETA
        |--------------------------------------------------------------------------
        |
        | Aquí se guarda:
        |
        | 1. Curación
        | 2. Detalles
        | 3. Distribución
        | 4. Salida de inventario
        | 5. Movimiento Caja Doctores
        | 6. Movimiento Caja Otros
        | 7. Movimiento Caja Empresa
        |
        | Si algo falla, se revierte todo.
        |
        */

        try {

            $curacion = DB::transaction(function () use ($request, $consultation, $doctorId, $porcentajeDoctor, $porcentajeOtros, $porcentajeEmpresa) {

                /*
                |--------------------------------------------------------------------------
                | GENERAR CÓDIGO
                |--------------------------------------------------------------------------
                */

                $ultimoId = Curacion::max('id') + 1;

                $codigo = 'CUR-' . str_pad(
                    $ultimoId,
                    6,
                    '0',
                    STR_PAD_LEFT
                );


                /*
                |--------------------------------------------------------------------------
                | CALCULAR TOTAL INSUMOS
                |--------------------------------------------------------------------------
                */

                $totalInsumos = 0;

                foreach ($request->detalles as $detalle) {

                    $cantidad = (float) $detalle['cantidad'];

                    $precio = (float) $detalle['precio_unitario'];

                    $subtotal = $cantidad * $precio;

                    $totalInsumos += $subtotal;
                }


                $totalInsumos = round(
                    $totalInsumos,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | COSTO DE CURACIÓN
                |--------------------------------------------------------------------------
                */

                $costoCuracion = round(
                    (float) $request->costo_curacion,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | TOTAL GENERAL
                |--------------------------------------------------------------------------
                |
                | Insumos + costo de curación
                |
                */

                $total = round(
                    $totalInsumos + $costoCuracion,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | CREAR CURACIÓN
                |--------------------------------------------------------------------------
                */

                $curacion = Curacion::create([

                    'codigo' => $codigo,

                    'consultation_id' =>
                        $request->consultation_id,

                    'fecha' =>
                        $request->fecha,

                    'descripcion' =>
                        $request->descripcion,

                    'costo_curacion' =>
                        $costoCuracion,

                    'total_insumos' =>
                        $totalInsumos,

                    'total' =>
                        $total,

                    'estado' =>
                        true,

                    'usuario_id' =>
                        Auth::id(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | CREAR DETALLES
                |--------------------------------------------------------------------------
                */

                foreach ($request->detalles as $detalle) {

                    $tipo = $detalle['tipo'];

                    $insumoId = null;

                    $agrupadoId = null;

                    $nombreOtro = null;


                    /*
                    |--------------------------------------------------------------------------
                    | INSUMO
                    |--------------------------------------------------------------------------
                    */

                    if ($tipo === 'insumo') {

                        $insumoId =
                            $detalle['insumo_id'] ?? null;

                        if (!$insumoId) {

                            throw new \Exception(
                                'Debe seleccionar un insumo.'
                            );
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | AGRUPADO
                    |--------------------------------------------------------------------------
                    */ elseif ($tipo === 'agrupado') {

                        $agrupadoId =
                            $detalle['insumo_agrupado_id']
                            ?? null;

                        if (!$agrupadoId) {

                            throw new \Exception(
                                'Debe seleccionar un insumo agrupado.'
                            );
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | OTRO
                    |--------------------------------------------------------------------------
                    */ elseif ($tipo === 'otro') {

                        $nombreOtro =
                            $detalle['nombre_otro']
                            ?? null;

                        if (!$nombreOtro) {

                            throw new \Exception(
                                'Debe indicar el nombre del elemento.'
                            );
                        }
                    }


                    $cantidad =
                        (float) $detalle['cantidad'];

                    $precio =
                        (float) $detalle['precio_unitario'];

                    $subtotal =
                        round(
                            $cantidad * $precio,
                            2
                        );


                    CuracionDetalle::create([

                        'curacion_id' =>
                            $curacion->id,

                        'insumo_id' =>
                            $insumoId,

                        'insumo_agrupado_id' =>
                            $agrupadoId,

                        'nombre_otro' =>
                            $nombreOtro,

                        'cantidad' =>
                            $cantidad,

                        'precio_unitario' =>
                            $precio,

                        'subtotal' =>
                            $subtotal,
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | DISTRIBUCIÓN
                |--------------------------------------------------------------------------
                |
                | IMPORTANTE:
                |
                | SOLO SE DISTRIBUYE costo_curacion.
                |
                | Los insumos NO entran a esta distribución.
                |
                */

                $montoDoctor = round(
                    $costoCuracion
                    * $porcentajeDoctor
                    / 100,
                    2
                );

                $montoOtros = round(
                    $costoCuracion
                    * $porcentajeOtros
                    / 100,
                    2
                );


                /*
                | La empresa recibe el saldo restante para
                | garantizar que la suma sea exactamente
                | igual al costo de la curación.
                */

                $montoEmpresa = round(
                    $costoCuracion
                    - $montoDoctor
                    - $montoOtros,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | DISTRIBUCIÓN DOCTOR
                |--------------------------------------------------------------------------
                */

                CuracionDistribucion::create([
                    'curacion_id' => $curacion->id,
                    'doctor_id' => $doctorId,
                    'concepto' => 'Curación ' . $curacion->codigo . ' - Doctor',
                    'porcentaje' => $porcentajeDoctor,
                    'monto' => $montoDoctor,
                ]);


                /*
                |--------------------------------------------------------------------------
                | DISTRIBUCIÓN OTROS
                |--------------------------------------------------------------------------
                */

                CuracionDistribucion::create([
                    'curacion_id' => $curacion->id,
                    'doctor_id' => null,
                    'concepto' => 'Curación ' . $curacion->codigo . ' - Otros',
                    'porcentaje' => $porcentajeOtros,
                    'monto' => $montoOtros,
                ]);


                /*
                |--------------------------------------------------------------------------
                | DISTRIBUCIÓN EMPRESA
                |--------------------------------------------------------------------------
                */

                CuracionDistribucion::create([
                    'curacion_id' => $curacion->id,
                    'doctor_id' => null,
                    'concepto' => 'Curación ' . $curacion->codigo . ' - Empresa',
                    'porcentaje' => $porcentajeEmpresa,
                    'monto' => $montoEmpresa,
                ]);


                /*
                |--------------------------------------------------------------------------
                | SALIDA DE INVENTARIO
                |--------------------------------------------------------------------------
                */

                $salida = SalidaInsumo::create([

                    'codigo' => 'SAL-' . str_pad(
                        SalidaInsumo::max('id') + 1,
                        6,
                        '0',
                        STR_PAD_LEFT
                    ),

                    'fecha' =>
                        $request->fecha,

                    'motivo' =>
                        'Salida por curacion - '
                        . $curacion->codigo,

                    'total' =>
                        0,

                    'monto_pagado' =>
                        0,

                    'saldo_pendiente' =>
                        0,

                    'observacion' =>
                        'Salida por Curación',

                    'usuario_id' =>
                        Auth::id(),
                ]);


                $totalSalida = 0;


                /*
                |--------------------------------------------------------------------------
                | PROCESAR INSUMOS
                |--------------------------------------------------------------------------
                */

                foreach ($curacion->detalles as $detalle) {

                    /*
                    |--------------------------------------------------------------------------
                    | INSUMO NORMAL
                    |--------------------------------------------------------------------------
                    */

                    if ($detalle->insumo_id) {

                        $insumo = Insumo::lockForUpdate()
                            ->findOrFail(
                                $detalle->insumo_id
                            );


                        $cantidad =
                            (float) $detalle->cantidad;


                        if (
                            (float) $insumo->stock
                            < $cantidad
                        ) {

                            throw new \Exception(
                                'Stock insuficiente para el insumo: '
                                . $insumo->nombre
                            );
                        }


                        $insumo->stock =
                            (float) $insumo->stock
                            - $cantidad;

                        $insumo->save();


                        $precioVenta =
                            (float) $insumo->precio_venta;


                        $subtotal =
                            round(
                                $cantidad * $precioVenta,
                                2
                            );


                        $totalSalida += $subtotal;


                        SalidaInsumoDetalle::create([

                            'salida_insumo_id' =>
                                $salida->id,

                            'insumo_id' =>
                                $insumo->id,

                            'cantidad' =>
                                $cantidad,

                            'precio_venta' =>
                                $precioVenta,

                            'subtotal' =>
                                $subtotal,
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | INSUMO AGRUPADO
                    |--------------------------------------------------------------------------
                    */ elseif ($detalle->insumo_agrupado_id) {

                        $agrupado =
                            InsumoAgrupado::with(
                                'detalles.insumo'
                            )->findOrFail(
                                    $detalle->insumo_agrupado_id
                                );


                        foreach (
                            $agrupado->detalles
                            as $componente
                        ) {

                            /*
                            | Los elementos "otros"
                            | no afectan inventario.
                            */

                            if (!$componente->insumo_id) {
                                continue;
                            }


                            $insumo =
                                Insumo::lockForUpdate()
                                    ->findOrFail(
                                        $componente->insumo_id
                                    );


                            $cantidadNecesaria =
                                (float) $componente->cantidad
                                * (float) $detalle->cantidad;


                            if (
                                (float) $insumo->stock
                                < $cantidadNecesaria
                            ) {

                                throw new \Exception(
                                    'Stock insuficiente para el insumo: '
                                    . $insumo->nombre
                                );
                            }


                            $insumo->stock =
                                (float) $insumo->stock
                                - $cantidadNecesaria;

                            $insumo->save();


                            $precioVenta =
                                (float) $insumo->precio_venta;


                            $subtotal =
                                round(
                                    $cantidadNecesaria
                                    * $precioVenta,
                                    2
                                );


                            $totalSalida += $subtotal;


                            SalidaInsumoDetalle::create([

                                'salida_insumo_id' =>
                                    $salida->id,

                                'insumo_id' =>
                                    $insumo->id,

                                'cantidad' =>
                                    $cantidadNecesaria,

                                'precio_venta' =>
                                    $precioVenta,

                                'subtotal' =>
                                    $subtotal,
                            ]);
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | OTRO
                    |--------------------------------------------------------------------------
                    |
                    | No afecta inventario.
                    |
                    */
                }


                /*
                |--------------------------------------------------------------------------
                | ACTUALIZAR SALIDA
                |--------------------------------------------------------------------------
                */

                $totalSalida =
                    round($totalSalida, 2);


                $salida->update([

                    'total' =>
                        $totalSalida,

                    'monto_pagado' =>
                        $totalSalida,

                    'saldo_pendiente' =>
                        0,
                ]);


                /*
                |--------------------------------------------------------------------------
                | CAJAS
                |--------------------------------------------------------------------------
                |
                | Las tres cajas se buscan por nombre.
                |
                | Doctores
                | Otros
                | Empresa
                |
                */

                $cajaDoctores = Caja::where(
                    'nombre',
                    'Doctores'
                )
                    ->where('estado', true)
                    ->lockForUpdate()
                    ->firstOrFail();


                $cajaOtros = Caja::where(
                    'nombre',
                    'Otros'
                )
                    ->where('estado', true)
                    ->lockForUpdate()
                    ->firstOrFail();


                $cajaEmpresa = Caja::where(
                    'nombre',
                    'Empresa'
                )
                    ->where('estado', true)
                    ->lockForUpdate()
                    ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | INGRESO CAJA DOCTORES
                |--------------------------------------------------------------------------
                */

                $this->registrarIngresoCaja(
                    $cajaDoctores,
                    $montoDoctor,
                    'Curación '
                    . $curacion->codigo
                    . ' - Doctor',
                    $curacion
                );


                /*
                |--------------------------------------------------------------------------
                | INGRESO CAJA OTROS
                |--------------------------------------------------------------------------
                */

                $this->registrarIngresoCaja(
                    $cajaOtros,
                    $montoOtros,
                    'Curación '
                    . $curacion->codigo
                    . ' - Otros',
                    $curacion
                );


                /*
                |--------------------------------------------------------------------------
                | INGRESO CAJA EMPRESA
                |--------------------------------------------------------------------------
                */

                $this->registrarIngresoCaja(
                    $cajaEmpresa,
                    $montoEmpresa,
                    'Curación '
                    . $curacion->codigo
                    . ' - Empresa',
                    $curacion
                );


                /*
                |--------------------------------------------------------------------------
                | RETORNAR CURACIÓN
                |--------------------------------------------------------------------------
                */

                return $curacion;
            });


            /*
            |--------------------------------------------------------------------------
            | REDIRECCIÓN
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'curaciones.show',
                    $curacion->id
                )
                ->with(
                    'success',
                    'Curación registrada correctamente. Código: '
                    . $curacion->codigo
                    . '. Se generaron los movimientos de caja correspondientes.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ERROR
        |--------------------------------------------------------------------------
        */ catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'error' =>
                        $e->getMessage()
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR INGRESO EN CAJA
    |--------------------------------------------------------------------------
    */

    private function registrarIngresoCaja(
        Caja $caja,
        float $monto,
        string $concepto,
        Curacion $curacion
    ): void {

        /*
        | Si el monto es cero no creamos
        | un movimiento innecesario.
        */

        if ($monto <= 0) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | SALDOS
        |--------------------------------------------------------------------------
        */

        $saldoAnterior =
            round(
                (float) $caja->saldo,
                2
            );


        $saldoNuevo =
            round(
                $saldoAnterior + $monto,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR CAJA
        |--------------------------------------------------------------------------
        */

        $caja->update([

            'saldo' =>
                $saldoNuevo,
        ]);


        /*
        |--------------------------------------------------------------------------
        | CREAR MOVIMIENTO
        |--------------------------------------------------------------------------
        */

        MovimientoCaja::create([

            'caja_id' =>
                $caja->id,

            'tipo' =>
                'ingreso',

            'concepto' =>
                $concepto,

            'monto' =>
                $monto,

            'saldo_anterior' =>
                $saldoAnterior,

            'saldo_nuevo' =>
                $saldoNuevo,

            'referencia_tipo' =>
                'curacion',

            'referencia_id' =>
                $curacion->id,

            'usuario_id' =>
                Auth::id(),

            'fecha' =>
                now(),

            'observacion' =>
                'Ingreso generado automáticamente por la distribución de la curación '
                . $curacion->codigo,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | MOSTRAR CURACIÓN
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $curacion = Curacion::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor',
            'detalles.insumo',
            'detalles.agrupado',
            'distribuciones.doctor',
            'receta.detalles',
        ])->findOrFail($id);

        return view(
            'curaciones.show',
            compact('curacion')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDITAR
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $curacion = Curacion::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor',
            'detalles',
            'distribuciones'
        ])->findOrFail($id);


        $consultations = Consultation::with([
            'appointment.patient',
            'appointment.doctor'
        ])
            ->orderByDesc('id')
            ->get();


        $insumos = Insumo::where(
            'estado',
            true
        )
            ->orderBy('nombre')
            ->get();


        $agrupados = InsumoAgrupado::where(
            'estado',
            true
        )
            ->orderBy('nombre')
            ->get();


        return view(
            'curaciones.edit',
            compact(
                'curacion',
                'consultations',
                'insumos',
                'agrupados'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        /*
        | Por seguridad no permitimos modificar
        | una curación que ya generó movimiento
        | de inventario y caja.
        */

        $curacion =
            Curacion::findOrFail($id);


        return redirect()
            ->route(
                'curaciones.show',
                $curacion->id
            )
            ->with(
                'info',
                'La edición de una curación registrada será habilitada posteriormente mediante un proceso de reversión de inventario y caja.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ANULAR
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $curacion =
            Curacion::findOrFail($id);


        /*
        | No eliminamos físicamente.
        */

        $curacion->update([

            'estado' =>
                false,
        ]);


        return redirect()
            ->route('curaciones.index')
            ->with(
                'success',
                'La curación fue anulada correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

    public function pdf($id)
    {
        $curacion = Curacion::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor',
            'detalles.insumo',
            'detalles.agrupado',
            'distribuciones.doctor',
            'receta.detalles',
        ])->findOrFail($id);


        $pdf = Pdf::loadView(
            'curaciones.pdf',
            compact('curacion')
        );


        return $pdf->stream(
            'Curacion-'
            . $curacion->codigo
            . '.pdf'
        );
    }
}