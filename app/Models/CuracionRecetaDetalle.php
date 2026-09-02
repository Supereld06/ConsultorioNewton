<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuracionRecetaDetalle extends Model
{
    use HasFactory;

    protected $table = 'curacion_receta_detalles';

    protected $fillable = [
        'curacion_receta_id',
        'medicamento',
        'dosis',
        'frecuencia',
        'duracion',
        'indicaciones',
    ];

    public function receta()
    {
        return $this->belongsTo(
            CuracionReceta::class,
            'curacion_receta_id'
        );
    }
}