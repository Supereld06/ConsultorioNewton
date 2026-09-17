<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Services\CajaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class CajaController extends Controller
{
    protected CajaService $cajaService;

    public function __construct(CajaService $cajaService)
    {
        $this->cajaService = $cajaService;
    }


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
    | FORMULARIO DE INGRESO
    |--------------------------------------------------------------------------
    */

    public function formularioIngreso($id)
    {
        $caja = Caja::where('estado', true)
            ->findOrFail($id);

        return view('cajas.ingreso', compact('caja'));
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR INGRESO
    |--------------------------------------------------------------------------
    */

    public function ingresar(Request $request, $id)
    {
        $request->validate([
            'monto' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'concepto' => [
                'required',
                'string',
                'max:255',
            ],

            'observacion' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser numérico.',
            'monto.min' => 'El monto debe ser mayor a cero.',
            'concepto.required' => 'El concepto es obligatorio.',
            'concepto.max' => 'El concepto no puede superar los 255 caracteres.',
        ]);

        try {

            $caja = Caja::where('estado', true)
                ->findOrFail($id);

            $this->cajaService->ingresar(
                $caja,
                (float) $request->monto,
                $request->concepto,
                'ingreso_manual',
                null,
                $request->observacion
            );

            return redirect()
                ->route('cajas.movimientos', $caja->id)
                ->with(
                    'success',
                    'Ingreso registrado correctamente.'
                );

        } catch (Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'monto' => $e->getMessage()
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO DE EGRESO
    |--------------------------------------------------------------------------
    */

    public function formularioEgreso($id)
    {
        $caja = Caja::where('estado', true)
            ->findOrFail($id);

        return view('cajas.egreso', compact('caja'));
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR EGRESO
    |--------------------------------------------------------------------------
    */

    public function egresar(Request $request, $id)
    {
        $request->validate([
            'monto' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'concepto' => [
                'required',
                'string',
                'max:255',
            ],

            'observacion' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser numérico.',
            'monto.min' => 'El monto debe ser mayor a cero.',
            'concepto.required' => 'El concepto es obligatorio.',
            'concepto.max' => 'El concepto no puede superar los 255 caracteres.',
        ]);

        try {

            $caja = Caja::where('estado', true)
                ->findOrFail($id);

            $this->cajaService->egresar(
                $caja,
                (float) $request->monto,
                $request->concepto,
                'egreso_manual',
                null,
                $request->observacion
            );

            return redirect()
                ->route('cajas.movimientos', $caja->id)
                ->with(
                    'success',
                    'Egreso registrado correctamente.'
                );

        } catch (Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'monto' => $e->getMessage()
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO DE TRANSFERENCIA
    |--------------------------------------------------------------------------
    */

    public function formularioTransferencia($id)
    {
        $caja = Caja::where('estado', true)
            ->findOrFail($id);

        $cajas = Caja::where('estado', true)
            ->where('id', '!=', $caja->id)
            ->orderBy('nombre')
            ->get();

        return view('cajas.transferencia', compact(
            'caja',
            'cajas'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | TRANSFERIR DINERO ENTRE CAJAS
    |--------------------------------------------------------------------------
    */

    public function transferir(Request $request, $id)
    {
        $request->validate([
            'caja_destino_id' => [
                'required',
                'exists:cajas,id',
                'different:' . $id,
            ],

            'monto' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'observacion' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'caja_destino_id.required' => 'Debes seleccionar una caja destino.',
            'caja_destino_id.exists' => 'La caja destino no existe.',
            'caja_destino_id.different' => 'La caja destino debe ser diferente a la caja origen.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser numérico.',
            'monto.min' => 'El monto debe ser mayor a cero.',
        ]);

        try {

            $origen = Caja::where('estado', true)
                ->findOrFail($id);

            $destino = Caja::where('estado', true)
                ->findOrFail($request->caja_destino_id);

            $this->cajaService->transferir(
                $origen,
                $destino,
                (float) $request->monto,
                $request->observacion
            );

            return redirect()
                ->route('cajas.index')
                ->with(
                    'success',
                    'Transferencia realizada correctamente.'
                );

        } catch (Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'monto' => $e->getMessage()
                ]);
        }
    }
}