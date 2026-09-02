<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsumoAgrupado extends Model
{
    use HasFactory;

    protected $table = 'insumo_agrupados';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'precio',
        'estado',
        'usuario_id',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'estado' => 'boolean',
    ];

    public function detalles()
    {
        return $this->hasMany(
            InsumoAgrupadoDetalle::class,
            'insumo_agrupado_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }
}