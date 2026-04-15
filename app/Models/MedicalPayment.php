<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalPayment extends Model
{

    protected $fillable = [
        'consultation_id',
        'doctor_id',
        'cost',
        'percentage_newton',
        'percentage_doctor',
        'cost_newton',
        'cost_doctor',
        'paid'
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
