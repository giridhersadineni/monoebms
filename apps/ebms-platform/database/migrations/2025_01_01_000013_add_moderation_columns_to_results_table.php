<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->smallInteger('marks_secured')->nullable()->after('is_absent_int');
            $table->smallInteger('etotal')->nullable()->after('marks_secured');
            $table->smallInteger('itotal')->nullable()->after('etotal');
            $table->smallInteger('floatation_marks')->nullable()->after('itotal');
            $table->smallInteger('float_deduct')->nullable()->after('floatation_marks');
            $table->string('fl_scriptcode', 50)->nullable()->after('float_deduct');
            $table->smallInteger('moderation_marks')->nullable()->after('fl_scriptcode');
            $table->smallInteger('ac_marks')->nullable()->after('moderation_marks');
            $table->boolean('is_moderated')->default(false)->after('ac_marks');
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn([
                'marks_secured', 'etotal', 'itotal', 'floatation_marks',
                'float_deduct', 'fl_scriptcode', 'moderation_marks', 'ac_marks', 'is_moderated',
            ]);
        });
    }
};
