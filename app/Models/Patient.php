<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'apellidos',
        'nombres',
        'fecha_nacimiento',
        'ci',
        'telefono',
        'email',
        'direccion',
        'genero',
        'fotografia'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
