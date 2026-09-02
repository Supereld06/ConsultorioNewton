<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuracionDetalle extends Model
{
    use HasFactory;

    protected $table = 'curacion_detalles';

    protected $fillable = [
        'curacion_id',
        'insumo_id',
        'insumo_agrupado_id',
        'nombre_otro',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function curacion()
    {
        return $this->belongsTo(
            Curacion::class,
            'curacion_id'
        );
    }

    public function insumo()
    {
        return $this->belongsTo(
            Insumo::class,
            'insumo_id'
        );
    }

    public function agrupado()
    {
        return $this->belongsTo(
            InsumoAgrupado::class,
            'insumo_agrupado_id'
        );
    }

    public function getNombreAttribute()
    {
        if ($this->insumo) {
            return $this->insumo->nombre;
        }

        if ($this->agrupado) {
            return $this->agrupado->nombre;
        }

        return $this->nombre_otro;
    }
}