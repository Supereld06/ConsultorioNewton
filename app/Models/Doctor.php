<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'nombres',
        'apellidos',
        'ci',
        'especialidad',
        'telefono',
        'foto',
        'hora_inicio',
        'hora_fin',
        'duracion_cita'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function pagosMedicos()
    {
        return $this->hasMany(
            PagoMedico::class,
            'doctor_id'
        );
    }

    public function liquidaciones()
    {
        return $this->hasMany(
            LiquidacionMedico::class,
            'doctor_id'
        );
    }
}
