<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salida_insumo_detalles', function (Blueprint $table) {

            $table->id();

            // Salida relacionada
            $table->foreignId('salida_insumo_id')
                ->constrained('salidas_insumos')
                ->cascadeOnDelete();

            // Insumo relacionado
            $table->foreignId('insumo_id')
                ->constrained('insumos')
                ->restrictOnDelete();

            // Cantidad retirada
            $table->decimal('cantidad', 10, 2);

            // Precio de venta utilizado
            $table->decimal('precio_venta', 10, 2);

            // Subtotal
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salida_insumo_detalles');
    }
};

