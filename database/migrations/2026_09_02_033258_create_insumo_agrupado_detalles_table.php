<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('insumo_agrupado_detalles', function (Blueprint $table) {

            $table->id();
            $table->foreignId('insumo_agrupado_id')
                ->constrained('insumo_agrupados')
                ->cascadeOnDelete();
            $table->foreignId('insumo_id')
                ->nullable()
                ->constrained('insumos')
                ->nullOnDelete();
            $table->string('nombre_otro')->nullable();
            $table->decimal('cantidad', 10, 2)->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insumo_agrupado_detalles');
    }
};