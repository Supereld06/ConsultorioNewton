<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuracionDistribucion extends Model
{
    use HasFactory;

    protected $table = 'curacion_distribuciones';

    protected $fillable = [
        'curacion_id',
        'doctor_id',
        'concepto',
        'porcentaje',
        'monto',
    ];

    protected $casts = [
        'porcentaje' => 'decimal:2',
        'monto' => 'decimal:2',
    ];

    public function curacion()
    {
        return $this->belongsTo(
            Curacion::class,
            'curacion_id'
        );
    }

    public function doctor()
    {
        return $this->belongsTo(
            Doctor::class,
            'doctor_id'
        );
    }
}