<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revaluation_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revaluation_enrollment_id')->constrained('revaluation_enrollments')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('subject_code', 10);

            $table->unique(['revaluation_enrollment_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revaluation_subjects');
    }
};
