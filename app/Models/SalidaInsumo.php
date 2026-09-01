<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaInsumo extends Model
{
    use HasFactory;

    protected $table = 'salidas_insumos';

    protected $fillable = [
        'codigo',
        'fecha',
        'motivo',
        'total',
        'monto_pagado',
        'saldo_pendiente',
        'observacion',
        'usuario_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON DETALLES
    |--------------------------------------------------------------------------
    */

    public function detalles()
    {
        return $this->hasMany(
            SalidaInsumoDetalle::class,
            'salida_insumo_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON USUARIO
    |--------------------------------------------------------------------------
    */

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }
}

