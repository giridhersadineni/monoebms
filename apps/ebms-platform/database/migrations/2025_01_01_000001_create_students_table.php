<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('hall_ticket', 60)->unique();
            $table->date('dob')->nullable();
            $table->string('dost_id', 50)->nullable()->index();
            $table->string('name', 60);
            $table->string('father_name', 60)->nullable();
            $table->string('mother_name', 60)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('aadhaar', 12)->nullable();
            $table->enum('gender', ['M', 'F', 'O'])->nullable();
            $table->string('caste', 10)->nullable();
            $table->string('sub_caste', 200)->nullable();
            $table->string('course', 20)->nullable();
            $table->string('course_name', 225)->nullable();
            $table->string('group_code', 50)->nullable();
            $table->string('medium', 3)->nullable();
            $table->tinyInteger('semester')->nullable();
            $table->integer('admission_year')->nullable();
            $table->string('scheme', 10)->nullable();
            $table->string('address', 60)->nullable();
            $table->string('address2', 60)->nullable();
            $table->string('mandal', 50)->nullable();
            $table->string('city', 30)->nullable();
            $table->string('state', 20)->nullable();
            $table->string('pincode', 10)->nullable();
            $table->enum('challenged_quota', ['NONE', 'PHYSICALLY CHALLENGED', 'VISUALLY CHALLENGED', 'PARTIALLY CHALLENGED'])->default('NONE');
            $table->string('apaar_id', 12)->nullable();
            $table->string('ssc_hall_ticket', 20)->nullable();
            $table->string('inter_hall_ticket', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('legacy_stid')->nullable()->index();
            $table->timestamps();

            $table->index('hall_ticket');
            $table->index(['course', 'scheme']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
