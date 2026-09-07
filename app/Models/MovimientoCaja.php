<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    protected $table = 'movimientos_caja';

    protected $fillable = [
        'caja_id',
        'tipo',
        'concepto',
        'monto',
        'saldo_anterior',
        'saldo_nuevo',
        'referencia_tipo',
        'referencia_id',
        'usuario_id',
        'fecha',
        'observacion',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'saldo_nuevo' => 'decimal:2',
        'fecha' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | CAJA
    |--------------------------------------------------------------------------
    */

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    /*
    |--------------------------------------------------------------------------
    | USUARIO
    |--------------------------------------------------------------------------
    */

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}