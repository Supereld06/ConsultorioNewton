<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'appointment_id',
        'atendido',
        'diagnostico',
        'tratamiento',
        'receta',
        'observaciones'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}