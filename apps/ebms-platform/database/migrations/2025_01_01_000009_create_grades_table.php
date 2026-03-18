<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained('students')->cascadeOnDelete();
            $table->string('hall_ticket', 60)->index();
            $table->string('memo_no', 20)->nullable();
            $table->decimal('part1_cgpa', 4, 2)->nullable();
            $table->decimal('part2_cgpa', 4, 2)->nullable();
            $table->decimal('all_cgpa', 4, 2)->nullable();
            $table->string('part1_division', 100)->nullable();
            $table->string('part2_division', 100)->nullable();
            $table->string('final_division', 100)->nullable();
            $table->text('part1_subjects')->nullable();
            $table->text('part2_subjects')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
