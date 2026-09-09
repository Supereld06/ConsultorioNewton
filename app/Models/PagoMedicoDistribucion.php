<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoMedicoDistribucion extends Model
{
    protected $table = 'pago_medico_distribuciones';

    protected $fillable = [
        'pago_medico_id',
        'doctor_id',
        'caja_id',
        'concepto',
        'porcentaje',
        'monto',
    ];

    protected $casts = [
        'porcentaje' => 'decimal:2',
        'monto' => 'decimal:2',
    ];

    public function pagoMedico()
    {
        return $this->belongsTo(
            PagoMedico::class,
            'pago_medico_id'
        );
    }

    public function doctor()
    {
        return $this->belongsTo(
            Doctor::class,
            'doctor_id'
        );
    }

    public function caja()
    {
        return $this->belongsTo(
            Caja::class,
            'caja_id'
        );
    }

    public function liquidacionDetalle()
    {
        return $this->hasOne(
            LiquidacionMedicoDetalle::class,
            'pago_medico_distribucion_id'
        );
    }
}