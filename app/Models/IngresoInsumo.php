<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoInsumo extends Model
{
    use HasFactory;

    protected $table = 'ingreso_insumos';

    protected $fillable = [
        'codigo',
        'fecha',
        'proveedor',
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

    public function detalles()
    {
        return $this->hasMany(
            IngresoInsumoDetalle::class,
            'ingreso_insumo_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }
}