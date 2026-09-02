<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('curacion_distribuciones', function (Blueprint $table) {

            $table->id();
            $table->foreignId('curacion_id')
                ->constrained('curaciones')
                ->cascadeOnDelete();
            $table->foreignId('doctor_id')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();
            $table->string('concepto');
            $table->decimal('porcentaje', 5, 2);
            $table->decimal('monto', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curacion_distribuciones');
    }

    
};