<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add flat fee columns, drop legacy fee_json
        Schema::table('exams', function (Blueprint $table) {
            $table->unsignedInteger('fee_regular')->nullable()->after('revaluation_open');
            $table->unsignedInteger('fee_supply_upto2')->nullable()->after('fee_regular');
            $table->unsignedInteger('fee_improvement')->nullable()->after('fee_supply_upto2');
            $table->unsignedInteger('fee_fine')->nullable()->after('fee_improvement');
            $table->dropColumn('fee_json');
        });

        // 2. Migrate existing status values before changing the enum
        DB::table('exams')->where('status', 'open')->update(['status' => 'RUNNING']);
        DB::table('exams')->whereIn('status', ['closed', 'cancelled'])->update(['status' => 'CLOSED']);

        // 3. Change the status enum to the new set of values
        DB::statement("ALTER TABLE exams MODIFY COLUMN status ENUM('NOTIFY','RUNNING','REVALOPEN','CLOSED') NOT NULL DEFAULT 'NOTIFY'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE exams MODIFY COLUMN status ENUM('open','closed','cancelled') NOT NULL DEFAULT 'open'");
        DB::table('exams')->where('status', 'RUNNING')->update(['status' => 'open']);
        DB::table('exams')->whereIn('status', ['NOTIFY', 'REVALOPEN', 'CLOSED'])->update(['status' => 'closed']);

        Schema::table('exams', function (Blueprint $table) {
            $table->json('fee_json')->nullable()->after('revaluation_open');
            $table->dropColumn(['fee_regular', 'fee_supply_upto2', 'fee_improvement', 'fee_fine']);
        });
    }
};
