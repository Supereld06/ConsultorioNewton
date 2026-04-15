<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
        protected $fillable = [
        'consultation_id',
        'name',
        'cost',
        'percentage_newton',
        'percentage_clinic',
        'cost_newton',
        'cost_clinic'
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
