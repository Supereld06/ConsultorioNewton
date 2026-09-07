<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO DE CAJAS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $cajas = Caja::where('estado', true)
            ->orderBy('id')
            ->get();

        $totalCajas = $cajas->sum('saldo');

        return view('cajas.index', compact(
            'cajas',
            'totalCajas'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | MOVIMIENTOS DE UNA CAJA
    |--------------------------------------------------------------------------
    */

    public function movimientos($id)
    {
        $caja = Caja::findOrFail($id);

        $movimientos = MovimientoCaja::with('usuario')
            ->where('caja_id', $caja->id)
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        return view('cajas.movimientos', compact(
            'caja',
            'movimientos'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO TRANSFERENCIA
    |--------------------------------------------------------------------------
    */

    public function formularioTransferencia()
    {
        $cajas = Caja::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('cajas.transferencia', compact('cajas'));
    }


    /*
    |--------------------------------------------------------------------------
    | TRANSFERIR DINERO ENTRE CAJAS
    |--------------------------------------------------------------------------
    */

    public function transferir(Request $request)
    {
        $request->validate([
            'caja_origen_id' => 'required|different:caja_destino_id|exists:cajas,id',
            'caja_destino_id' => 'required|exists:cajas,id',
            'monto' => 'required|numeric|min:0.01',
            'observacion' => 'nullable|string|max:1000',
        ]);

        try {

            DB::transaction(function () use ($request) {

                $origen = Caja::lockForUpdate()
                    ->findOrFail($request->caja_origen_id);

                $destino = Caja::lockForUpdate()
                    ->findOrFail($request->caja_destino_id);

                $monto = (float) $request->monto;


                /*
                 * Verificar saldo
                 */

                if ($origen->saldo < $monto) {

                    throw new \Exception(
                        'Saldo insuficiente en la caja ' .
                        $origen->nombre .
                        '. Saldo disponible: Bs. ' .
                        number_format($origen->saldo, 2) .
                        '. Monto solicitado: Bs. ' .
                        number_format($monto, 2)
                    );
                }


                $saldoAnteriorOrigen = (float) $origen->saldo;

                $saldoAnteriorDestino = (float) $destino->saldo;


                $nuevoSaldoOrigen =
                    $saldoAnteriorOrigen - $monto;

                $nuevoSaldoDestino =
                    $saldoAnteriorDestino + $monto;


                /*
                 * Actualizar origen
                 */

                $origen->update([
                    'saldo' => $nuevoSaldoOrigen,
                ]);


                /*
                 * Actualizar destino
                 */

                $destino->update([
                    'saldo' => $nuevoSaldoDestino,
                ]);


                /*
                 * Movimiento de salida
                 */

                MovimientoCaja::create([

                    'caja_id' => $origen->id,

                    'tipo' => 'egreso',

                    'concepto' =>
                        'Transferencia a ' .
                        $destino->nombre,

                    'monto' => $monto,

                    'saldo_anterior' =>
                        $saldoAnteriorOrigen,

                    'saldo_nuevo' =>
                        $nuevoSaldoOrigen,

                    'referencia_tipo' =>
                        'transferencia',

                    'usuario_id' =>
                        Auth::id(),

                    'fecha' =>
                        now(),

                    'observacion' =>
                        $request->observacion,

                ]);


                /*
                 * Movimiento de entrada
                 */

                MovimientoCaja::create([

                    'caja_id' => $destino->id,

                    'tipo' => 'ingreso',

                    'concepto' =>
                        'Transferencia desde ' .
                        $origen->nombre,

                    'monto' => $monto,

                    'saldo_anterior' =>
                        $saldoAnteriorDestino,

                    'saldo_nuevo' =>
                        $nuevoSaldoDestino,

                    'referencia_tipo' =>
                        'transferencia',

                    'usuario_id' =>
                        Auth::id(),

                    'fecha' =>
                        now(),

                    'observacion' =>
                        $request->observacion,

                ]);

            });


            return redirect()
                ->route('cajas.index')
                ->with(
                    'success',
                    'Transferencia realizada correctamente.'
                );


        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'monto' => $e->getMessage()
                ]);
        }
    }
}