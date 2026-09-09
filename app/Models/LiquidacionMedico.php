<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiquidacionMedico extends Model
{
    protected $table = 'liquidaciones_medicos';

    protected $fillable = [
        'numero',
        'doctor_id',
        'fecha',
        'total_generado',
        'total_pagado',
        'saldo',
        'estado',
        'usuario_id',
        'observacion',
    ];

    protected $casts = [
        'fecha' => 'date',
        'total_generado' => 'decimal:2',
        'total_pagado' => 'decimal:2',
        'saldo' => 'decimal:2',
    ];

    public function doctor()
    {
        return $this->belongsTo(
            Doctor::class,
            'doctor_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }

    public function detalles()
    {
        return $this->hasMany(
            LiquidacionMedicoDetalle::class,
            'liquidacion_medico_id'
        );
    }
}