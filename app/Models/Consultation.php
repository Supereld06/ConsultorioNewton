<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'appointment_id',
        'atendido',
        'motivo_consulta',
        'cuadro_clinico',
        'estudios',
        'receta',
        'tratamiento',
        'diagnostico',
        'observaciones'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }

    public function medicalPayments()
    {
        return $this->hasMany(MedicalPayment::class);
    }

    // Una consulta puede tener una curación
    public function curacion()
    {
        return $this->hasOne(Curacion::class, 'consultation_id');
    }

    public function estudiosComplementarios()
    {
        return $this->hasMany(
            EstudioComplementario::class,
            'consultation_id'
        );
    }
}

