<?php

namespace App\Services;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use Illuminate\Support\Facades\Auth;

class CajaService
{
    /*
    |--------------------------------------------------------------------------
    | INGRESO
    |--------------------------------------------------------------------------
    */

    public function ingresar(
        Caja $caja,
        float $monto,
        string $concepto,
        ?string $referenciaTipo = null,
        ?int $referenciaId = null,
        ?string $observacion = null
    ): MovimientoCaja {

        if ($monto <= 0) {
            throw new \Exception(
                'El monto del ingreso debe ser mayor a cero.'
            );
        }

        $caja = Caja::lockForUpdate()
            ->findOrFail($caja->id);

        $saldoAnterior = (float) $caja->saldo;

        $saldoNuevo = $saldoAnterior + $monto;

        $caja->update([
            'saldo' => $saldoNuevo,
        ]);

        return MovimientoCaja::create([
            'caja_id' => $caja->id,
            'tipo' => 'ingreso',
            'concepto' => $concepto,
            'monto' => $monto,
            'saldo_anterior' => $saldoAnterior,
            'saldo_nuevo' => $saldoNuevo,
            'referencia_tipo' => $referenciaTipo,
            'referencia_id' => $referenciaId,
            'usuario_id' => Auth::id(),
            'fecha' => now(),
            'observacion' => $observacion,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | EGRESO
    |--------------------------------------------------------------------------
    */

    public function egresar(
        Caja $caja,
        float $monto,
        string $concepto,
        ?string $referenciaTipo = null,
        ?int $referenciaId = null,
        ?string $observacion = null
    ): MovimientoCaja {

        if ($monto <= 0) {
            throw new \Exception(
                'El monto del egreso debe ser mayor a cero.'
            );
        }

        $caja = Caja::lockForUpdate()
            ->findOrFail($caja->id);

        $saldoAnterior = (float) $caja->saldo;

        /*
         * Verificar saldo
         */

        if ($saldoAnterior < $monto) {

            throw new \Exception(
                'Saldo insuficiente en la caja ' .
                $caja->nombre .
                '. Saldo disponible: Bs. ' .
                number_format($saldoAnterior, 2)
            );
        }

        $saldoNuevo = $saldoAnterior - $monto;

        $caja->update([
            'saldo' => $saldoNuevo,
        ]);

        return MovimientoCaja::create([
            'caja_id' => $caja->id,
            'tipo' => 'egreso',
            'concepto' => $concepto,
            'monto' => $monto,
            'saldo_anterior' => $saldoAnterior,
            'saldo_nuevo' => $saldoNuevo,
            'referencia_tipo' => $referenciaTipo,
            'referencia_id' => $referenciaId,
            'usuario_id' => Auth::id(),
            'fecha' => now(),
            'observacion' => $observacion,
        ]);
    }
}