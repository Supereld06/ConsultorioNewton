<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('liquidacion_medico_detalles', function (Blueprint $table) {

            $table->id();

            $table->foreignId('liquidacion_medico_id')
                ->constrained('liquidaciones_medicos')
                ->cascadeOnDelete();

            /*
             * Indica de dónde proviene el dinero:
             *
             * pago_medico = atención médica
             * curacion    = curación
             */
            $table->enum('tipo_origen', [
                'pago_medico',
                'curacion'
            ]);

            /*
             * ID del registro de origen.
             *
             * Si tipo_origen = pago_medico
             * apunta a pago_medico_distribuciones.id
             *
             * Si tipo_origen = curacion
             * apunta al registro correspondiente de distribución
             * de la curación.
             */
            $table->unsignedBigInteger('origen_id');

            $table->string('concepto');

            $table->date('fecha');

            $table->decimal('monto', 12, 2);

            $table->timestamps();

            $table->index(
                ['tipo_origen', 'origen_id'],
                'liq_det_origen_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidacion_medico_detalles');
    }
};