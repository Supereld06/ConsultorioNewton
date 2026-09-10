<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PagoMedicoDistribucion;
use App\Models\CuracionDistribucion;

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
     * Obtener la distribución que originó este detalle.
     */
    public function getOrigenAttribute()
    {
        if ($this->tipo_origen === 'pago_medico') {
            return PagoMedicoDistribucion::with([
                'pagoMedico.consultation.appointment.patient'
            ])->find($this->origen_id);
        }

        if ($this->tipo_origen === 'curacion') {
            return CuracionDistribucion::with([
                'curacion.consultation.appointment.patient'
            ])->find($this->origen_id);
        }

        return null;
    }
}