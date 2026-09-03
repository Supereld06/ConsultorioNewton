<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estudios_complementarios', function (Blueprint $table) {
            $table->id();

            $table->string('codigo')->unique();

            $table->foreignId('consultation_id')
                ->constrained('consultations')
                ->restrictOnDelete();

            $table->foreignId('doctor_id')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();

            $table->date('fecha');

            // Totales
            $table->decimal('total_cobrado', 10, 2)->default(0);
            $table->decimal('total_laboratorio', 10, 2)->default(0);
            $table->decimal('utilidad', 10, 2)->default(0);

            // Pago del paciente
            $table->decimal('monto_pagado_paciente', 10, 2)->default(0);
            $table->decimal('saldo_paciente', 10, 2)->default(0);

            // Pago al laboratorio
            $table->decimal('monto_pagado_laboratorio', 10, 2)->default(0);
            $table->decimal('saldo_laboratorio', 10, 2)->default(0);

            $table->boolean('estado')->default(true);

            $table->text('observaciones')->nullable();

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudios_complementarios');
    }
};