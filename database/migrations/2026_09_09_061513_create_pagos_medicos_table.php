<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pagos_medicos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consultation_id')
                ->unique()
                ->constrained('consultations')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->restrictOnDelete();

            $table->decimal('costo_atencion', 12, 2);

            $table->date('fecha_atencion');

            $table->time('hora_atencion');

            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('estado', [
                'registrado',
                'liquidado',
                'anulado'
            ])->default('registrado');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_medicos');
    }
};