<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->tinyInteger('semester');
            $table->string('course', 20);
            $table->enum('exam_type', ['regular', 'supplementary', 'revaluation', 'improvement'])->default('regular');
            $table->string('month', 13)->nullable();
            $table->year('year');
            $table->enum('status', ['NOTIFY', 'RUNNING', 'REVALOPEN', 'CLOSED'])->default('NOTIFY');
            $table->string('scheme', 10)->nullable();
            $table->boolean('revaluation_open')->default(false);
            $table->unsignedInteger('fee_regular')->nullable();
            $table->unsignedInteger('fee_supply_upto2')->nullable();
            $table->unsignedInteger('fee_improvement')->nullable();
            $table->unsignedInteger('fee_fine')->nullable();
            $table->timestamps();

            $table->index(['semester', 'course', 'status']);
            $table->index(['exam_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
