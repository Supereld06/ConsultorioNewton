<?php

namespace App\Http\Controllers;

use App\Models\Curacion;
use App\Models\CuracionDetalle;
use App\Models\CuracionDistribucion;
use App\Models\Consultation;
use App\Models\Insumo;
use App\Models\InsumoAgrupado;
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
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'fecha' => 'required|date',

            'descripcion' => 'nullable|string',

            'costo_curacion' => 'required|numeric|min:0',

            'detalles' => 'required|array|min:1',

            'detalles.*.tipo' => 'required|in:insumo,agrupado,otro',

            'detalles.*.cantidad' => 'required|numeric|min:0.01',

            'detalles.*.precio_unitario' => 'required|numeric|min:0',

            'porcentaje_doctor' => 'required|numeric|min:0|max:100',
            'porcentaje_enfermera' => 'required|numeric|min:0|max:100',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VALIDAR DISTRIBUCIÓN
        |--------------------------------------------------------------------------
        */

        $porcentajeDoctor = (float) $request->porcentaje_doctor;
        $porcentajeEnfermera = (float) $request->porcentaje_enfermera;

        $porcentajeNewton = 100
            - $porcentajeDoctor
            - $porcentajeEnfermera;

        if ($porcentajeNewton < 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'porcentaje_doctor' =>
                        'La suma de los porcentajes no puede superar el 100%.'
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
        ])->findOrFail($request->consultation_id);

        $doctorId = optional($consultation->appointment)->doctor_id;


        /*
        |--------------------------------------------------------------------------
        | TRANSACCIÓN
        |--------------------------------------------------------------------------
        */

        try {

            $curacion = DB::transaction(function () use ($request, $consultation, $doctorId, $porcentajeDoctor, $porcentajeEnfermera, $porcentajeNewton) {

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
                | CALCULAR TOTAL DE INSUMOS
                |--------------------------------------------------------------------------
                */

                $totalInsumos = 0;

                foreach ($request->detalles as $detalle) {

                    $cantidad = (float) $detalle['cantidad'];
                    $precio = (float) $detalle['precio_unitario'];

                    $subtotal = $cantidad * $precio;

                    $totalInsumos += $subtotal;
                }


                /*
                |--------------------------------------------------------------------------
                | COSTO DE CURACIÓN
                |--------------------------------------------------------------------------
                */

                $costoCuracion = (float) $request->costo_curacion;

                $total = $totalInsumos + $costoCuracion;


                /*
                |--------------------------------------------------------------------------
                | CREAR CURACIÓN
                |--------------------------------------------------------------------------
                */

                $curacion = Curacion::create([
                    'codigo' => $codigo,
                    'consultation_id' => $request->consultation_id,
                    'fecha' => $request->fecha,
                    'descripcion' => $request->descripcion,
                    'costo_curacion' => $costoCuracion,
                    'total_insumos' => $totalInsumos,
                    'total' => $total,
                    'estado' => true,
                    'usuario_id' => Auth::id(),
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

                    if ($tipo === 'insumo') {

                        $insumoId = $detalle['insumo_id'] ?? null;

                        if (!$insumoId) {
                            throw new \Exception(
                                'Debe seleccionar un insumo.'
                            );
                        }

                    } elseif ($tipo === 'agrupado') {

                        $agrupadoId =
                            $detalle['insumo_agrupado_id'] ?? null;

                        if (!$agrupadoId) {
                            throw new \Exception(
                                'Debe seleccionar un insumo agrupado.'
                            );
                        }

                    } elseif ($tipo === 'otro') {

                        $nombreOtro =
                            $detalle['nombre_otro'] ?? null;

                        if (!$nombreOtro) {
                            throw new \Exception(
                                'Debe indicar el nombre del elemento.'
                            );
                        }
                    }


                    $cantidad = (float) $detalle['cantidad'];
                    $precio = (float) $detalle['precio_unitario'];

                    $subtotal = $cantidad * $precio;


                    CuracionDetalle::create([
                        'curacion_id' => $curacion->id,
                        'insumo_id' => $insumoId,
                        'insumo_agrupado_id' => $agrupadoId,
                        'nombre_otro' => $nombreOtro,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precio,
                        'subtotal' => $subtotal,
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | DISTRIBUCIÓN DEL COSTO DE LA CURACIÓN
                |--------------------------------------------------------------------------
                |
                | IMPORTANTE:
                | La distribución se realiza solamente sobre
                | costo_curacion, NO sobre los insumos.
                |
                */

                $montoDoctor =
                    round($costoCuracion * $porcentajeDoctor / 100, 2);

                $montoEnfermera =
                    round($costoCuracion * $porcentajeEnfermera / 100, 2);

                $montoNewton =
                    round($costoCuracion * $porcentajeNewton / 100, 2);


                /*
                |--------------------------------------------------------------------------
                | DOCTOR
                |--------------------------------------------------------------------------
                */

                CuracionDistribucion::create([
                    'curacion_id' => $curacion->id,
                    'doctor_id' => $doctorId,
                    'concepto' => 'Doctor',
                    'porcentaje' => $porcentajeDoctor,
                    'monto' => $montoDoctor,
                ]);


                /*
                |--------------------------------------------------------------------------
                | ENFERMERA
                |--------------------------------------------------------------------------
                */

                CuracionDistribucion::create([
                    'curacion_id' => $curacion->id,
                    'doctor_id' => null,
                    'concepto' => 'Enfermera',
                    'porcentaje' => $porcentajeEnfermera,
                    'monto' => $montoEnfermera,
                ]);


                /*
                |--------------------------------------------------------------------------
                | NEWTON / CONSULTORIO
                |--------------------------------------------------------------------------
                */

                CuracionDistribucion::create([
                    'curacion_id' => $curacion->id,
                    'doctor_id' => null,
                    'concepto' => 'Newton / Consultorio',
                    'porcentaje' => $porcentajeNewton,
                    'monto' => $montoNewton,
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

                    'fecha' => $request->fecha,

                    // Aquí guardamos el código de la curación
                    'motivo' => $curacion->codigo,

                    'total' => 0,
                    'monto_pagado' => 0,
                    'saldo_pendiente' => 0,

                    // Concepto de la salida
                    'observacion' => 'Salida por Curación',

                    'usuario_id' => Auth::id(),
                ]);


                $totalSalida = 0;


                /*
                |--------------------------------------------------------------------------
                | PROCESAR LOS INSUMOS
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
                            ->findOrFail($detalle->insumo_id);

                        $cantidad = (float) $detalle->cantidad;


                        if ((float) $insumo->stock < $cantidad) {

                            throw new \Exception(
                                "Stock insuficiente para el insumo: "
                                . $insumo->nombre
                            );
                        }


                        $insumo->stock =
                            (float) $insumo->stock - $cantidad;

                        $insumo->save();


                        $precioVenta =
                            (float) $insumo->precio_venta;

                        $subtotal =
                            $cantidad * $precioVenta;

                        $totalSalida += $subtotal;


                        SalidaInsumoDetalle::create([
                            'salida_insumo_id' => $salida->id,
                            'insumo_id' => $insumo->id,
                            'cantidad' => $cantidad,
                            'precio_venta' => $precioVenta,
                            'subtotal' => $subtotal,
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | INSUMO AGRUPADO
                    |--------------------------------------------------------------------------
                    */ elseif ($detalle->insumo_agrupado_id) {

                        $agrupado = InsumoAgrupado::with('detalles.insumo')
                            ->findOrFail(
                                $detalle->insumo_agrupado_id
                            );


                        foreach ($agrupado->detalles as $componente) {

                            /*
                            | Si es un "otro", no afecta inventario.
                            */

                            if (!$componente->insumo_id) {
                                continue;
                            }


                            $insumo = Insumo::lockForUpdate()
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
                                    "Stock insuficiente para el insumo: "
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
                                $cantidadNecesaria
                                * $precioVenta;

                            $totalSalida += $subtotal;


                            SalidaInsumoDetalle::create([
                                'salida_insumo_id' => $salida->id,
                                'insumo_id' => $insumo->id,
                                'cantidad' => $cantidadNecesaria,
                                'precio_venta' => $precioVenta,
                                'subtotal' => $subtotal,
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
                | ACTUALIZAR TOTAL DE LA SALIDA
                |--------------------------------------------------------------------------
                */

                $salida->update([
                    'total' => $totalSalida,
                ]);


                return $curacion;
            });


            return redirect()
                ->route('curaciones.show', $curacion->id)
                ->with(
                    'success',
                    'Curación registrada correctamente. Código: '
                    . $curacion->codigo
                );


        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
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

        return view('curaciones.show', compact('curacion'));
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

        $insumos = Insumo::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $agrupados = InsumoAgrupado::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('curaciones.edit', compact(
            'curacion',
            'consultations',
            'insumos',
            'agrupados'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        /*
        | Por seguridad, no vamos a permitir modificar una curación
        | que ya generó movimientos de inventario.
        |
        | Esto evita duplicar o descuadrar el stock.
        */

        $curacion = Curacion::findOrFail($id);

        return redirect()
            ->route('curaciones.show', $curacion->id)
            ->with(
                'info',
                'La edición de una curación registrada será habilitada posteriormente mediante un proceso de reversión de inventario.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR / ANULAR
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $curacion = Curacion::findOrFail($id);

        /*
        | No eliminamos físicamente porque existen movimientos
        | de inventario y distribución de dinero.
        */

        $curacion->update([
            'estado' => false,
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
            'Curacion-' . $curacion->codigo . '.pdf'
        );
    }
}