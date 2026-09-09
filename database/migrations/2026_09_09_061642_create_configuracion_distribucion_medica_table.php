<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('configuracion_distribucion_medica', function (Blueprint $table) {
            $table->id();

            $table->decimal('porcentaje_medico', 5, 2)
                ->default(70);

            $table->decimal('porcentaje_institucion', 5, 2)
                ->default(20);

            $table->decimal('porcentaje_otros', 5, 2)
                ->default(10);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_distribucion_medica');
    }
};