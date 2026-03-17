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
        Schema::create('doctors', function (Blueprint $table) {

            $table->id();
            $table->string('apellidos');
            $table->string('nombres');
            $table->string('ci')->unique();
            $table->string('especialidad');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
