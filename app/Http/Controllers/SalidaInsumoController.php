<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\SalidaInsumo;
use App\Models\SalidaInsumoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Caja;
use App\Services\CajaService;

class SalidaInsumoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO DE SALIDAS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $buscar = $request->input('buscar');


        $query = SalidaInsumo::with('usuario');

        if ($buscar) {

            $query->where(function ($q) use ($buscar) {

                $q->where('codigo', 'like', '%' . $buscar . '%')
                    ->orWhere('motivo', 'like', '%' . $buscar . '%')

                    ->orWhereHas('usuario', function ($q) use ($buscar) {
                        $q->where('name', 'like', '%' . $buscar . '%');
                    });

            });
        }

        $salidas = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'insumos.salidas.index',
            compact('salidas')
        );


    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO NUEVA SALIDA
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $insumos = Insumo::where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        $cajas = Caja::where('estado', true)
            ->orderBy('id')
            ->get();

        return view(
            'insumos.salidas.create',
            compact('insumos', 'cajas')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR SALIDA
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'fecha' => 'required|date',

            'motivo' => 'nullable|string|max:255',

            'caja_id' => 'required|integer|exists:cajas,id',

            'insumos' => 'required|array|min:1',

            'insumos.*.id' => 'required|exists:insumos,id',

            'insumos.*.cantidad' => 'required|numeric|min:0.01',

            'monto_pagado' => 'required|numeric|min:0',

            'observacion' => 'nullable|string',

        ]);


        DB::beginTransaction();

        try {
            $cajaService = app(CajaService::class);
            /*
            |--------------------------------------------------------------------------
            | CALCULAR TOTAL
            |--------------------------------------------------------------------------
            */

            $total = 0;

            foreach ($request->insumos as $item) {

                $insumo = Insumo::lockForUpdate()
                    ->findOrFail($item['id']);

                /*
                |--------------------------------------------------------------------------
                | COMPROBAR STOCK
                |--------------------------------------------------------------------------
                */

                if ($item['cantidad'] > $insumo->stock) {

                    throw new \Exception(
                        'Stock insuficiente para el insumo: '
                        . $insumo->nombre
                        . '. Stock disponible: '
                        . $insumo->stock
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | PRECIO DE VENTA
                |--------------------------------------------------------------------------
                */

                $precioVenta = $insumo->precio_venta;

                $subtotal = $item['cantidad'] * $precioVenta;

                $total += $subtotal;
            }


            /*
            |--------------------------------------------------------------------------
            | MONTO PAGADO
            |--------------------------------------------------------------------------
            */

            $montoPagado = $request->monto_pagado;

            if ($montoPagado > $total) {

                throw new \Exception(
                    'El monto pagado no puede ser mayor al total de la salida.'
                );
            }


            $saldoPendiente = $total - $montoPagado;

            $caja = Caja::findOrFail($request->caja_id);

            /*
            |--------------------------------------------------------------------------
            | GENERAR CÓDIGO
            |--------------------------------------------------------------------------
            */

            $ultimaSalida = SalidaInsumo::latest('id')->first();

            if ($ultimaSalida) {

                $numero = $ultimaSalida->id + 1;

            } else {

                $numero = 1;
            }

            $codigo = 'SAL-' . str_pad(
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

            $salida = SalidaInsumo::create([

                'codigo' => $codigo,
                'fecha' => $request->fecha,
                'motivo' => $request->motivo,
                'total' => $total,
                'monto_pagado' => $montoPagado,
                'saldo_pendiente' => $saldoPendiente,
                'observacion' => $request->observacion,
                'usuario_id' => auth()->id(),

            ]);

            /*
|--------------------------------------------------------------------------
| INGRESO A CAJA
|--------------------------------------------------------------------------
*/

            if ($montoPagado > 0) {

                $cajaService->ingresar(
                    $caja,
                    $montoPagado,
                    'Venta de insumos',
                    'SalidaInsumo',
                    $salida->id,
                    'Ingreso por venta de insumos - ' . $codigo
                );

            }

            /*
            |--------------------------------------------------------------------------
            | CREAR DETALLES Y ACTUALIZAR STOCK
            |--------------------------------------------------------------------------
            */

            foreach ($request->insumos as $item) {

                $insumo = Insumo::lockForUpdate()
                    ->findOrFail($item['id']);

                $cantidad = $item['cantidad'];

                $precioVenta = $insumo->precio_venta;

                $subtotal = $cantidad * $precioVenta;


                /*
                |--------------------------------------------------------------------------
                | DETALLE
                |--------------------------------------------------------------------------
                */

                SalidaInsumoDetalle::create([

                    'salida_insumo_id' => $salida->id,

                    'insumo_id' => $insumo->id,

                    'cantidad' => $cantidad,

                    'precio_venta' => $precioVenta,

                    'subtotal' => $subtotal,

                ]);


                /*
                |--------------------------------------------------------------------------
                | DESCONTAR STOCK
                |--------------------------------------------------------------------------
                */

                $insumo->decrement(
                    'stock',
                    $cantidad
                );
            }


            DB::commit();


            return redirect()
                ->route('insumos.salidas.index')
                ->with(
                    'success',
                    'Salida registrada correctamente. Código: '
                    . $codigo
                );


        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PDF DE LA SALIDA
    |--------------------------------------------------------------------------
    */

    public function pdf($id)
    {
        $salida = SalidaInsumo::with([
            'usuario',
            'detalles.insumo'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'insumos.salidas.pdf',
            compact('salida')
        );

        return $pdf->stream(
            $salida->codigo . '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RECIBO DE LA SALIDA
    |--------------------------------------------------------------------------
    */

    public function recibo($id)
    {
        $salida = SalidaInsumo::with([
            'usuario',
            'detalles.insumo'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'insumos.salidas.recibo',
            compact('salida')
        );

        return $pdf->stream(
            'Recibo-' . $salida->codigo . '.pdf'
        );
    }


}

