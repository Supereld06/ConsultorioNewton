<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\IngresoInsumo;
use App\Models\IngresoInsumoDetalle;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class IngresoInsumoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;

        $ingresos = IngresoInsumo::with([
            'usuario',
            'detalles.insumo'
        ])
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'codigo',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'proveedor',
                            'like',
                            "%{$search}%"
                        );

                });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'insumos.ingresos.index',
            compact(
                'ingresos',
                'search'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $insumos = Insumo::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view(
            'insumos.ingresos.create',
            compact('insumos')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR INGRESO
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'fecha' => [
                'required',
                'date'
            ],

            'proveedor' => [
                'nullable',
                'string',
                'max:255'
            ],

            'observacion' => [
                'nullable',
                'string'
            ],

            'monto_pagado' => [
                'required',
                'numeric',
                'min:0'
            ],

            'productos' => [
                'required',
                'array',
                'min:1'
            ],

            'productos.*.insumo_id' => [
                'required',
                'integer',
                'exists:insumos,id'
            ],

            'productos.*.cantidad' => [
                'required',
                'numeric',
                'gt:0'
            ],

            'productos.*.precio_compra' => [
                'required',
                'numeric',
                'min:0'
            ],

            'productos.*.precio_venta' => [
                'required',
                'numeric',
                'min:0'
            ],

        ], [

            'fecha.required' =>
                'La fecha del ingreso es obligatoria.',

            'monto_pagado.required' =>
                'Debe ingresar el monto pagado.',

            'productos.required' =>
                'Debe agregar al menos un insumo.',

            'productos.min' =>
                'Debe agregar al menos un insumo.',

            'productos.*.cantidad.gt' =>
                'La cantidad debe ser mayor a cero.',

            'productos.*.precio_compra.min' =>
                'El precio de compra no puede ser negativo.',

            'productos.*.precio_venta.min' =>
                'El precio de venta no puede ser negativo.',

        ]);


        try {

            DB::transaction(function () use ($request) {

                /*
                |--------------------------------------------------------------------------
                | CALCULAR TOTAL
                |--------------------------------------------------------------------------
                */

                $total = 0;

                foreach ($request->productos as $producto) {

                    $cantidad =
                        (float) $producto['cantidad'];

                    $precio =
                        (float) $producto['precio_compra'];

                    $total +=
                        $cantidad * $precio;
                }


                /*
                |--------------------------------------------------------------------------
                | MONTO PAGADO
                |--------------------------------------------------------------------------
                */

                $montoPagado =
                    (float) $request->monto_pagado;


                /*
                |--------------------------------------------------------------------------
                | VALIDAR QUE NO PAGUE MÁS DEL TOTAL
                |--------------------------------------------------------------------------
                */

                if ($montoPagado > $total) {

                    throw new \Exception(
                        'El monto pagado no puede ser mayor al total del ingreso.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | SALDO PENDIENTE
                |--------------------------------------------------------------------------
                */

                $saldoPendiente =
                    $total - $montoPagado;


                /*
                |--------------------------------------------------------------------------
                | GENERAR CÓDIGO
                |--------------------------------------------------------------------------
                */

                $ultimo =
                    IngresoInsumo::latest('id')->first();

                $numero =
                    $ultimo
                    ? $ultimo->id + 1
                    : 1;

                $codigo =
                    'ING-' .
                    str_pad(
                        $numero,
                        6,
                        '0',
                        STR_PAD_LEFT
                    );


                /*
                |--------------------------------------------------------------------------
                | CREAR CABECERA
                |--------------------------------------------------------------------------
                */

                $ingreso =
                    IngresoInsumo::create([

                        'codigo' =>
                            $codigo,

                        'fecha' =>
                            $request->fecha,

                        'proveedor' =>
                            $request->proveedor,

                        'total' =>
                            $total,

                        'monto_pagado' =>
                            $montoPagado,

                        'saldo_pendiente' =>
                            $saldoPendiente,

                        'observacion' =>
                            $request->observacion,

                        'usuario_id' =>
                            auth()->id(),

                    ]);


                /*
                |--------------------------------------------------------------------------
                | DETALLES Y STOCK
                |--------------------------------------------------------------------------
                */

                foreach ($request->productos as $producto) {

                    $insumo =
                        Insumo::lockForUpdate()
                            ->findOrFail(
                                $producto['insumo_id']
                            );


                    $cantidad =
                        (float) $producto['cantidad'];

                    $precioCompra =
                        (float) $producto['precio_compra'];

                        $precioVenta =
                        (float) $producto['precio_venta'];

                    $subtotal =
                        $cantidad * $precioCompra;


                    /*
                    |--------------------------------------------------------------------------
                    | STOCK ANTERIOR
                    |--------------------------------------------------------------------------
                    */

                    $stockAnterior =
                        (float) $insumo->stock;


                    /*
                    |--------------------------------------------------------------------------
                    | NUEVO STOCK
                    |--------------------------------------------------------------------------
                    */

                    $stockNuevo =
                        $stockAnterior + $cantidad;


                    /*
                    |--------------------------------------------------------------------------
                    | DETALLE
                    |--------------------------------------------------------------------------
                    */

                    IngresoInsumoDetalle::create([

                        'ingreso_insumo_id' =>
                            $ingreso->id,

                        'insumo_id' =>
                            $insumo->id,

                        'cantidad' =>
                            $cantidad,

                        'precio_compra' =>
                            $precioCompra,

                        'precio_venta' =>
                            $precioVenta,

                        'subtotal' =>
                            $subtotal,

                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | ACTUALIZAR INSUMO
                    |--------------------------------------------------------------------------
                    */

                    $insumo->update([

                        'stock' =>
                            $stockNuevo,

                        'precio_compra' =>
                            $precioCompra,

                        'precio_venta' =>
                            $precioVenta,

                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | MOVIMIENTO
                    |--------------------------------------------------------------------------
                    */

                    MovimientoInventario::create([

                        'insumo_id' =>
                            $insumo->id,

                        'tipo_movimiento' =>
                            'INGRESO',

                        'cantidad' =>
                            $cantidad,

                        'stock_anterior' =>
                            $stockAnterior,

                        'stock_nuevo' =>
                            $stockNuevo,

                        'motivo' =>
                            'Ingreso ' . $codigo,

                        'usuario_id' =>
                            auth()->id(),

                    ]);

                }

            });


            return redirect()
                ->route('insumos.ingresos.index')
                ->with(
                    'success',
                    'Ingreso de insumos registrado correctamente.'
                );


        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    public function pdf($id)
    {
        $ingreso = IngresoInsumo::with([
            'usuario',
            'detalles.insumo'
        ])
            ->findOrFail($id);

        $pdf = PDF::loadView(
            'insumos.ingresos.pdf',
            compact('ingreso')
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            "Ingreso_{$ingreso->codigo}.pdf"
        );
    }

    
}