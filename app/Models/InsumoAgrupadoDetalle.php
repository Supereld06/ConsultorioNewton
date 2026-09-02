<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsumoAgrupadoDetalle extends Model
{
    use HasFactory;

    protected $table = 'insumo_agrupado_detalles';

    protected $fillable = [
        'insumo_agrupado_id',
        'insumo_id',
        'nombre_otro',
        'cantidad',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
    ];

    public function agrupado()
    {
        return $this->belongsTo(
            InsumoAgrupado::class,
            'insumo_agrupado_id'
        );
    }

    public function insumo()
    {
        return $this->belongsTo(
            Insumo::class,
            'insumo_id'
        );
    }

    public function getNombreAttribute()
    {
        if ($this->insumo) {
            return $this->insumo->nombre;
        }

        return $this->nombre_otro;
    }
}