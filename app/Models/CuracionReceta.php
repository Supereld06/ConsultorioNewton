<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuracionReceta extends Model
{
    use HasFactory;

    protected $table = 'curacion_recetas';

    protected $fillable = [
        'curacion_id',
        'indicaciones',
    ];

    public function curacion()
    {
        return $this->belongsTo(
            Curacion::class,
            'curacion_id'
        );
    }

    public function detalles()
    {
        return $this->hasMany(
            CuracionRecetaDetalle::class,
            'curacion_receta_id'
        );
    }
}