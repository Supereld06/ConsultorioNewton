<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Caja;

class CajaSeeder extends Seeder
{
    public function run(): void
    {
        Caja::create([
            'nombre' => 'Doctores',
            'saldo' => 400,
            'estado' => true,
        ]);

        Caja::create([
            'nombre' => 'Empresa',
            'saldo' => 500,
            'estado' => true,
        ]);

        Caja::create([
            'nombre' => 'Otros',
            'saldo' => 1200,
            'estado' => true,
        ]);
    }
}