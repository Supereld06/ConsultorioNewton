<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('curaciones', function (Blueprint $table) {

            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('consultation_id')
                ->constrained('consultations')
                ->restrictOnDelete();
            $table->date('fecha');
            $table->text('descripcion')->nullable();
            $table->decimal('costo_curacion', 10, 2)
                ->default(0);
            $table->decimal('total_insumos', 10, 2)
                ->default(0);
            $table->decimal('total', 10, 2)
                ->default(0);
            $table->boolean('estado')
                ->default(true);
            $table->foreignId('usuario_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curaciones');
    }
};