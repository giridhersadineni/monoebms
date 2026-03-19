<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_fee_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->string('course', 10)->nullable();     // 'BA', 'BCOM', 'BSC'; null = all courses
            $table->string('group_code', 20)->nullable(); // 'HEP', 'CA'; null = all groups in course
            $table->unsignedInteger('fee_regular')->nullable();
            $table->unsignedInteger('fee_supply_upto2')->nullable();
            $table->unsignedInteger('fee_improvement')->nullable();
            $table->unsignedInteger('fee_fine')->default(0);
            $table->timestamps();

            $table->unique(['exam_id', 'course', 'group_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_fee_rules');
    }
};
