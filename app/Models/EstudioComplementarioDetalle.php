<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudioComplementarioDetalle extends Model
{
    use HasFactory;

    protected $table = 'estudio_complementario_detalles';

    protected $fillable = [
        'estudio_complementario_id',
        'nombre_estudio',
        'tipo',
        'laboratorio',
        'precio_laboratorio',
        'precio_cobrado',
        'utilidad',
        'observaciones',
    ];

    protected $casts = [
        'precio_laboratorio' => 'decimal:2',
        'precio_cobrado' => 'decimal:2',
        'utilidad' => 'decimal:2',
    ];

    public function estudio()
    {
        return $this->belongsTo(
            EstudioComplementario::class,
            'estudio_complementario_id'
        );
    }
}