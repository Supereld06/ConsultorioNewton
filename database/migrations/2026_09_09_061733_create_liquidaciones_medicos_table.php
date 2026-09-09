<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('liquidaciones_medicos', function (Blueprint $table) {
            $table->id();

            $table->string('numero')
                ->unique();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->restrictOnDelete();

            $table->date('fecha');

            $table->decimal('total_generado', 12, 2)
                ->default(0);

            $table->decimal('total_pagado', 12, 2)
                ->default(0);

            $table->decimal('saldo', 12, 2)
                ->default(0);

            $table->enum('estado', [
                'pendiente',
                'pagada',
                'anulada'
            ])->default('pendiente');

            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('observacion')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidaciones_medicos');
    }
};