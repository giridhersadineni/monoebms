<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rename fee_per_subject → fee_supply_upto2 and drop fee_mode
        Schema::table('exams', function (Blueprint $table) {
            $table->renameColumn('fee_per_subject', 'fee_supply_upto2');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('fee_mode');
        });

        // Migrate existing status values before changing the enum
        DB::table('exams')->where('status', 'open')->update(['status' => 'RUNNING']);
        DB::table('exams')->whereIn('status', ['closed', 'cancelled'])->update(['status' => 'CLOSED']);

        // Change the status enum to the new set of values
        DB::statement("ALTER TABLE exams MODIFY COLUMN status ENUM('NOTIFY','RUNNING','REVALOPEN','CLOSED') NOT NULL DEFAULT 'NOTIFY'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE exams MODIFY COLUMN status ENUM('open','closed','cancelled') NOT NULL DEFAULT 'open'");
        DB::table('exams')->where('status', 'RUNNING')->update(['status' => 'open']);
        DB::table('exams')->whereIn('status', ['NOTIFY', 'REVALOPEN', 'CLOSED'])->update(['status' => 'closed']);

        Schema::table('exams', function (Blueprint $table) {
            $table->string('fee_mode', 20)->default('flat')->after('revaluation_open');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->renameColumn('fee_supply_upto2', 'fee_per_subject');
        });
    }
};
