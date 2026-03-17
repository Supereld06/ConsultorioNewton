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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();

            // 🔥 CONTROL PRINCIPAL
            $table->boolean('atendido')->default(false);

            // 🩺 DATOS MÉDICOS
            $table->text('diagnostico')->nullable();
            $table->text('tratamiento')->nullable();
            $table->text('receta')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
