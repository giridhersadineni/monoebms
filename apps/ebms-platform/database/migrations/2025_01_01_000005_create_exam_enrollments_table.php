<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('hall_ticket', 60)->index();
            $table->string('exam_type', 20)->default('regular');
            $table->unsignedInteger('fee_amount')->default(0);
            $table->timestamp('fee_paid_at')->nullable();
            $table->string('challan_number', 50)->nullable();
            $table->date('challan_submitted_on')->nullable();
            $table->string('challan_received_by', 50)->nullable();
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamps();

            $table->unique(['exam_id', 'student_id']);
            $table->index(['exam_id', 'fee_paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_enrollments');
    }
};
