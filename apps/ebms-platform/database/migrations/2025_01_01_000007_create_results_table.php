<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('exam_enrollments')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('hall_ticket', 60)->index();
            $table->unsignedBigInteger('exam_id')->index();
            $table->tinyInteger('ext_marks')->nullable();
            $table->tinyInteger('int_marks')->nullable();
            $table->unsignedSmallInteger('total_marks')->default(0);
            $table->string('grade', 7)->nullable();
            $table->enum('result', ['P', 'F', 'R', 'M', 'AB'])->default('F');
            $table->decimal('credits', 4, 1)->default(0);
            $table->decimal('gp_value', 5, 2)->default(0);
            $table->decimal('gp_credits', 6, 2)->default(0);
            $table->boolean('is_malpractice')->default(false);
            $table->boolean('is_absent_ext')->default(false);
            $table->boolean('is_absent_int')->default(false);
            $table->smallInteger('marks_secured')->nullable();    // MARKS_SECURED (raw ext before moderation)
            $table->smallInteger('etotal')->nullable();           // ETOTAL (ext max possible)
            $table->smallInteger('itotal')->nullable();           // ITOTAL (int max possible)
            $table->smallInteger('floatation_marks')->nullable(); // FLOATATION_MARKS
            $table->smallInteger('float_deduct')->nullable();     // FLOAT_DEDUCT
            $table->string('fl_scriptcode', 50)->nullable();      // FL_SCRIPTCODE
            $table->smallInteger('moderation_marks')->nullable(); // MODERATION_MARKS
            $table->smallInteger('ac_marks')->nullable();         // AC_MARKS
            $table->boolean('is_moderated')->default(false);      // IS_MODERATED
            $table->boolean('passed_by_floatation')->default(false);
            $table->tinyInteger('part')->default(1);
            $table->tinyInteger('semester')->default(1);
            $table->tinyInteger('attempts')->default(1);
            $table->timestamps();

            $table->index(['hall_ticket', 'exam_id']);
            $table->index(['enrollment_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
