<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudioComplementario extends Model
{
    use HasFactory;

    protected $table = 'estudios_complementarios';

    protected $fillable = [
        'codigo',
        'consultation_id',
        'doctor_id',
        'fecha',
        'total_cobrado',
        'total_laboratorio',
        'utilidad',
        'monto_pagado_paciente',
        'saldo_paciente',
        'monto_pagado_laboratorio',
        'saldo_laboratorio',
        'estado',
        'observaciones',
        'usuario_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'total_cobrado' => 'decimal:2',
        'total_laboratorio' => 'decimal:2',
        'utilidad' => 'decimal:2',
        'monto_pagado_paciente' => 'decimal:2',
        'saldo_paciente' => 'decimal:2',
        'monto_pagado_laboratorio' => 'decimal:2',
        'saldo_laboratorio' => 'decimal:2',
        'estado' => 'boolean',
    ];

    public function consultation()
    {
        return $this->belongsTo(
            Consultation::class,
            'consultation_id'
        );
    }

    public function doctor()
    {
        return $this->belongsTo(
            Doctor::class,
            'doctor_id'
        );
    }

    public function detalles()
    {
        return $this->hasMany(
            EstudioComplementarioDetalle::class,
            'estudio_complementario_id'
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