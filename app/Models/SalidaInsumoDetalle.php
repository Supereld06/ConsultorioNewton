<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaInsumoDetalle extends Model
{
    use HasFactory;

    protected $table = 'salida_insumo_detalles';

    protected $fillable = [
        'salida_insumo_id',
        'insumo_id',
        'cantidad',
        'precio_venta',
        'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON SALIDA
    |--------------------------------------------------------------------------
    */

    public function salida()
    {
        return $this->belongsTo(
            SalidaInsumo::class,
            'salida_insumo_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON INSUMO
    |--------------------------------------------------------------------------
    */

    public function insumo()
    {
        return $this->belongsTo(
            Insumo::class,
            'insumo_id'
        );
    }
}

