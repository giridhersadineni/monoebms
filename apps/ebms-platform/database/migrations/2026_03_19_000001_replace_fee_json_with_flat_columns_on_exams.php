<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Guard: skip column additions if the base migration already has them
        // (the create_exams migration was consolidated to include flat fee columns).
        if (!Schema::hasColumn('exams', 'fee_regular')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->unsignedInteger('fee_regular')->nullable()->after('revaluation_open');
                $table->unsignedInteger('fee_supply_upto2')->nullable()->after('fee_regular');
                $table->unsignedInteger('fee_improvement')->nullable()->after('fee_supply_upto2');
                $table->unsignedInteger('fee_fine')->nullable()->after('fee_improvement');
            });
        }

        if (Schema::hasColumn('exams', 'fee_json')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->dropColumn('fee_json');
            });
        }

        // Migrate existing status values (safe on MariaDB; no-op on fresh SQLite)
        DB::table('exams')->where('status', 'open')->update(['status' => 'RUNNING']);
        DB::table('exams')->whereIn('status', ['closed', 'cancelled'])->update(['status' => 'CLOSED']);

        // Change the status enum — only on MariaDB/MySQL (SQLite has no ENUM type)
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE exams MODIFY COLUMN status ENUM('NOTIFY','RUNNING','REVALOPEN','CLOSED') NOT NULL DEFAULT 'NOTIFY'");
        }
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
