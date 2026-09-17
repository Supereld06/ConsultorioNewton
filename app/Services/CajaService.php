<?php

namespace App\Services;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        |--------------------------------------------------------------------------
        | VERIFICAR SALDO
        |--------------------------------------------------------------------------
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


    /*
    |--------------------------------------------------------------------------
    | TRANSFERENCIA ENTRE CAJAS
    |--------------------------------------------------------------------------
    */

    public function transferir(
        Caja $origen,
        Caja $destino,
        float $monto,
        ?string $observacion = null
    ): array {

        if ($monto <= 0) {

            throw new \Exception(
                'El monto de la transferencia debe ser mayor a cero.'
            );
        }

        if ($origen->id === $destino->id) {

            throw new \Exception(
                'La caja de origen y destino no pueden ser la misma.'
            );
        }

        return DB::transaction(function () use ($origen, $destino, $monto, $observacion) {

            /*
            |--------------------------------------------------------------------------
            | BLOQUEAR AMBAS CAJAS
            |--------------------------------------------------------------------------
            */

            $origen = Caja::lockForUpdate()
                ->findOrFail($origen->id);

            $destino = Caja::lockForUpdate()
                ->findOrFail($destino->id);


            /*
            |--------------------------------------------------------------------------
            | VERIFICAR SALDO
            |--------------------------------------------------------------------------
            */

            $saldoAnteriorOrigen = (float) $origen->saldo;

            if ($saldoAnteriorOrigen < $monto) {

                throw new \Exception(
                    'Saldo insuficiente en la caja ' .
                    $origen->nombre .
                    '. Saldo disponible: Bs. ' .
                    number_format($saldoAnteriorOrigen, 2) .
                    '. Monto solicitado: Bs. ' .
                    number_format($monto, 2)
                );
            }


            /*
            |--------------------------------------------------------------------------
            | SALDOS
            |--------------------------------------------------------------------------
            */

            $saldoAnteriorDestino = (float) $destino->saldo;

            $saldoNuevoOrigen =
                $saldoAnteriorOrigen - $monto;

            $saldoNuevoDestino =
                $saldoAnteriorDestino + $monto;


            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR CAJA ORIGEN
            |--------------------------------------------------------------------------
            */

            $origen->update([
                'saldo' => $saldoNuevoOrigen,
            ]);


            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR CAJA DESTINO
            |--------------------------------------------------------------------------
            */

            $destino->update([
                'saldo' => $saldoNuevoDestino,
            ]);


            /*
            |--------------------------------------------------------------------------
            | MOVIMIENTO DE EGRESO
            |--------------------------------------------------------------------------
            */

            $movimientoSalida = MovimientoCaja::create([
                'caja_id' => $origen->id,
                'tipo' => 'egreso',
                'concepto' => 'Transferencia a ' . $destino->nombre,
                'monto' => $monto,
                'saldo_anterior' => $saldoAnteriorOrigen,
                'saldo_nuevo' => $saldoNuevoOrigen,
                'referencia_tipo' => 'transferencia',
                'usuario_id' => Auth::id(),
                'fecha' => now(),
                'observacion' => $observacion,
            ]);


            /*
            |--------------------------------------------------------------------------
            | MOVIMIENTO DE INGRESO
            |--------------------------------------------------------------------------
            */

            $movimientoEntrada = MovimientoCaja::create([
                'caja_id' => $destino->id,
                'tipo' => 'ingreso',
                'concepto' => 'Transferencia desde ' . $origen->nombre,
                'monto' => $monto,
                'saldo_anterior' => $saldoAnteriorDestino,
                'saldo_nuevo' => $saldoNuevoDestino,
                'referencia_tipo' => 'transferencia',
                'usuario_id' => Auth::id(),
                'fecha' => now(),
                'observacion' => $observacion,
            ]);


            return [
                'salida' => $movimientoSalida,
                'entrada' => $movimientoEntrada,
            ];
        });
    }
}