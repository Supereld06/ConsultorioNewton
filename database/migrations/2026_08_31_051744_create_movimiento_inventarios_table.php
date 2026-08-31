<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {

            $table->id();
            $table->foreignId('insumo_id')
                ->constrained('insumos')
                ->restrictOnDelete();
            $table->enum('tipo_movimiento', [
                'INGRESO',
                'SALIDA',
                'AJUSTE_ENTRADA',
                'AJUSTE_SALIDA'
            ]);
            $table->decimal('cantidad', 10, 2);
            $table->decimal('stock_anterior', 10, 2);
            $table->decimal('stock_nuevo', 10, 2);
            $table->string('motivo')->nullable();
            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};