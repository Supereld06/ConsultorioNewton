<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoMedico extends Model
{
    protected $table = 'pagos_medicos';

    protected $fillable = [
        'consultation_id',
        'doctor_id',
        'costo_atencion',
        'fecha_atencion',
        'hora_atencion',
        'usuario_id',
        'estado',
    ];

    protected $casts = [
        'costo_atencion' => 'decimal:2',
        'fecha_atencion' => 'date',
        'hora_atencion' => 'datetime:H:i',
    ];

    public function consultation()
    {
        return $this->belongsTo(
            Consultation::class,
            'consultation_id'
        );
    }

    public function doctor()
    {
        return $this->belongsTo(
            Doctor::class,
            'doctor_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }

    public function distribuciones()
    {
        return $this->hasMany(
            PagoMedicoDistribucion::class,
            'pago_medico_id'
        );
    }
}