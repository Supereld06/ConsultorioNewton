<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curacion extends Model
{
    use HasFactory;

    protected $table = 'curaciones';

    protected $fillable = [
        'codigo',
        'consultation_id',
        'fecha',
        'descripcion',
        'costo_curacion',
        'total_insumos',
        'total',
        'estado',
        'usuario_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'costo_curacion' => 'decimal:2',
        'total_insumos' => 'decimal:2',
        'total' => 'decimal:2',
        'estado' => 'boolean',
    ];

    public function consultation()
    {
        return $this->belongsTo(
            Consultation::class,
            'consultation_id'
        );
    }

    public function detalles()
    {
        return $this->hasMany(
            CuracionDetalle::class,
            'curacion_id'
        );
    }

    public function distribuciones()
    {
        return $this->hasMany(
            CuracionDistribucion::class,
            'curacion_id'
        );
    }

    public function receta()
    {
        return $this->hasOne(
            CuracionReceta::class,
            'curacion_id'
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