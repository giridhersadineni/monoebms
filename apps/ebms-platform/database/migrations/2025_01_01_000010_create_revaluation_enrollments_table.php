<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revaluation_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('original_enrollment_id')->constrained('exam_enrollments')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('hall_ticket', 60)->index();
            $table->unsignedInteger('fee_amount')->default(0);
            $table->timestamp('fee_paid_at')->nullable();
            $table->string('challan_number', 50)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'processed'])->default('pending');
            $table->timestamps();

            $table->unique(['exam_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revaluation_enrollments');
    }
};
