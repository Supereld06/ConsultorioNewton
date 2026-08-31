<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ingreso_insumos', function (Blueprint $table) {

            $table->id();

            $table->string('codigo')->unique();
            $table->date('fecha');
            $table->string('proveedor')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('monto_pagado', 10, 2)->default(0);
            $table->decimal('saldo_pendiente', 10, 2)->default(0);
            $table->text('observacion')->nullable();
            $table->foreignId('usuario_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingreso_insumos');
    }
};