<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gpas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->unique()->constrained('exam_enrollments')->cascadeOnDelete();
            $table->string('hall_ticket', 60)->index();
            $table->unsignedBigInteger('exam_id');
            $table->decimal('sgpa', 4, 2)->default(0);
            $table->decimal('cgpa', 4, 2)->default(0);
            $table->unsignedSmallInteger('total_marks')->default(0);
            $table->enum('result', ['P', 'F', 'R', 'M'])->default('F');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gpas');
    }
};
