<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estudio_complementario_detalles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudio_complementario_id');

            $table->foreign('estudio_complementario_id', 'fk_estudio_detalle')
                ->references('id')
                ->on('estudios_complementarios')
                ->onDelete('cascade');

            $table->string('nombre_estudio');
            $table->string('tipo')->nullable();
            $table->string('laboratorio')->nullable();
            $table->decimal('precio_laboratorio', 10, 2)->default(0);
            $table->decimal('precio_cobrado', 10, 2)->default(0);
            $table->decimal('utilidad', 10, 2)->default(0);
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudio_complementario_detalles');
    }
};