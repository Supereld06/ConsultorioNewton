<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ingreso_insumo_detalles', function (Blueprint $table) {

            $table->id();
            $table->foreignId('ingreso_insumo_id')
                ->constrained('ingreso_insumos')
                ->cascadeOnDelete();
            $table->foreignId('insumo_id')
                ->constrained('insumos')
                ->restrictOnDelete();
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio_compra', 10, 2);
            $table->decimal('precio_venta', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingreso_insumo_detalles');
    }
};