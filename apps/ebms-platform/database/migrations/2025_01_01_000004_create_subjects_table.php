<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name', 100);
            $table->string('course', 20);
            $table->string('group_code', 10)->nullable();
            $table->string('medium', 3)->nullable();
            $table->tinyInteger('semester');
            $table->enum('paper_type', ['compulsory', 'elective'])->default('compulsory');
            $table->string('elective_group', 5)->nullable();
            $table->tinyInteger('part')->default(1);
            $table->string('scheme', 10)->nullable();
            $table->timestamps();

            $table->index(['course', 'semester', 'scheme']);
            $table->index(['course', 'group_code', 'medium']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
