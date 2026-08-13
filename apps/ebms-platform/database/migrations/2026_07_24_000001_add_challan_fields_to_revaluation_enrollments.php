<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('revaluation_enrollments', function (Blueprint $table) {
            $table->date('challan_submitted_on')->nullable()->after('challan_number');
            $table->string('challan_received_by', 50)->nullable()->after('challan_submitted_on');
        });
    }

    public function down(): void
    {
        Schema::table('revaluation_enrollments', function (Blueprint $table) {
            $table->dropColumn(['challan_submitted_on', 'challan_received_by']);
        });
    }
};
