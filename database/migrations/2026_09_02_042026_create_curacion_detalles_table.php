<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('curacion_detalles', function (Blueprint $table) {

            $table->id();

            $table->foreignId('curacion_id')
                ->constrained('curaciones')
                ->cascadeOnDelete();
            $table->foreignId('insumo_id')
                ->nullable()
                ->constrained('insumos')
                ->nullOnDelete();
            $table->foreignId('insumo_agrupado_id')
                ->nullable()
                ->constrained('insumo_agrupados')
                ->nullOnDelete();
            $table->string('nombre_otro')
                ->nullable();
            $table->decimal('cantidad', 10, 2)
                ->default(1);
            $table->decimal('precio_unitario', 10, 2)
                ->default(0);
            $table->decimal('subtotal', 10, 2)
                ->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curacion_detalles');
    }
};