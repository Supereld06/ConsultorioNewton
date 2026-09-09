<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiquidacionMedicoDetalle extends Model
{
    protected $table = 'liquidacion_medico_detalles';

    protected $fillable = [
        'liquidacion_medico_id',
        'tipo_origen',
        'origen_id',
        'concepto',
        'fecha',
        'monto',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
    ];

    public function liquidacion()
    {
        return $this->belongsTo(
            LiquidacionMedico::class,
            'liquidacion_medico_id'
        );
    }

    /**
     * Distribución de pago médico.
     *
     * Se utiliza cuando:
     * tipo_origen = pago_medico
     */
    public function pagoMedicoDistribucion()
    {
        return $this->belongsTo(
            PagoMedicoDistribucion::class,
            'origen_id'
        );
    }

    /**
     * Distribución de curación.
     *
     * Se utiliza cuando:
     * tipo_origen = curacion
     */
    public function curacionDistribucion()
    {
        return $this->belongsTo(
            CuracionDistribucion::class,
            'origen_id'
        );
    }
}