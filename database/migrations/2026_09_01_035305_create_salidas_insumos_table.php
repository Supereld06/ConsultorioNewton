<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salidas_insumos', function (Blueprint $table) {

            $table->id();

            // Código de la salida
            $table->string('codigo')->unique();

            // Fecha de la salida
            $table->date('fecha');

            // Motivo de la salida
            $table->string('motivo')->nullable();

            // Total de la salida
            $table->decimal('total', 10, 2)->default(0);

            // Monto pagado
            $table->decimal('monto_pagado', 10, 2)->default(0);

            // Saldo pendiente
            $table->decimal('saldo_pendiente', 10, 2)->default(0);

            // Observación
            $table->text('observacion')->nullable();

            // Usuario que registra
            $table->foreignId('usuario_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salidas_insumos');
    }
};

