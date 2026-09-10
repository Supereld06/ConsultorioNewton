<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pago_medico_distribuciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pago_medico_id')
                ->constrained('pagos_medicos')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();

            $table->foreignId('caja_id')
                ->constrained('cajas')
                ->restrictOnDelete();

            $table->string('concepto', 100);


            $table->decimal('porcentaje', 5, 2);

            $table->decimal('monto', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago_medico_distribuciones');
    }
};