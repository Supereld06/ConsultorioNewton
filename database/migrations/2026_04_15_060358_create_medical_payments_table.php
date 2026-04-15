<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medical_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consultation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();

            $table->decimal('cost', 10, 2); // costo atención

            $table->decimal('percentage_newton', 5, 2);
            $table->decimal('percentage_doctor', 5, 2);

            $table->decimal('cost_newton', 10, 2);
            $table->decimal('cost_doctor', 10, 2);

            $table->boolean('paid')->default(false); // 🔥

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_payments');
    }
};