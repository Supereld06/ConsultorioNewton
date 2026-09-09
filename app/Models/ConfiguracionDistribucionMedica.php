<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionDistribucionMedica extends Model
{
    protected $table = 'configuracion_distribucion_medica';

    protected $fillable = [
        'porcentaje_medico',
        'porcentaje_institucion',
        'porcentaje_otros',
    ];

    protected $casts = [
        'porcentaje_medico' => 'decimal:2',
        'porcentaje_institucion' => 'decimal:2',
        'porcentaje_otros' => 'decimal:2',
    ];


    
}