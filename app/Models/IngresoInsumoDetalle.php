<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoInsumoDetalle extends Model
{
    use HasFactory;

    protected $table = 'ingreso_insumo_detalles';

    protected $fillable = [
        'ingreso_insumo_id',
        'insumo_id',
        'cantidad',
        'precio_compra',
        'precio_venta',
        'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_compra' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function ingreso()
    {
        return $this->belongsTo(
            IngresoInsumo::class,
            'ingreso_insumo_id'
        );
    }

    public function insumo()
    {
        return $this->belongsTo(
            Insumo::class,
            'insumo_id'
        );
    }
}