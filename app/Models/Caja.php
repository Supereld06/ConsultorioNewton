<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'cajas';

    protected $fillable = [
        'nombre',
        'saldo',
        'estado',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
        'estado' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | MOVIMIENTOS
    |--------------------------------------------------------------------------
    */

    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class);
    }
}