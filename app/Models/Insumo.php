<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $table = 'insumos';

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'descripcion',
        'unidad_medida',
        'stock',
        'stock_minimo',
        'precio_compra',
        'precio_venta',
        'estado',
    ];

    protected $casts = [
        'stock' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'estado' => 'boolean',
    ];

    public function ingresos()
    {
        return $this->hasMany(
            IngresoInsumoDetalle::class,
            'insumo_id'
        );
    }

    public function movimientos()
    {
        return $this->hasMany(
            MovimientoInventario::class,
            'insumo_id'
        );
    }
}