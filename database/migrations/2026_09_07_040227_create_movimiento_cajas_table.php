<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movimientos_caja', function (Blueprint $table) {

            $table->id();

            $table->foreignId('caja_id')
                ->constrained('cajas')
                ->restrictOnDelete();

            $table->enum('tipo', [
                'ingreso',
                'egreso'
            ]);

            $table->string('concepto');

            $table->decimal('monto', 12, 2);

            $table->decimal('saldo_anterior', 12, 2);

            $table->decimal('saldo_nuevo', 12, 2);

            /*
             * Permite relacionar el movimiento
             * con otro módulo del sistema.
             *
             * Ejemplos:
             * atencion
             * pago_doctor
             * compra
             * transferencia
             */
            $table->string('referencia_tipo')
                ->nullable();

            $table->unsignedBigInteger('referencia_id')
                ->nullable();

            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('fecha');

            $table->text('observacion')
                ->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_caja');
    }
};