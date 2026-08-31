<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('insumos', function (Blueprint $table) {

            $table->id();
            $table->string('codigo', 50)->unique();
            $table->string('nombre');
            $table->enum('tipo', [
                'MEDICAMENTO',
                'INSUMO_MEDICO'
            ]);
            $table->text('descripcion')->nullable();
            $table->string('unidad_medida', 50)->default('UNIDAD');
            $table->decimal('stock', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->decimal('precio_compra', 10, 2)->default(0);
            $table->decimal('precio_venta', 10, 2)->default(0);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};