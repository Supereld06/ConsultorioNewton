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
}
