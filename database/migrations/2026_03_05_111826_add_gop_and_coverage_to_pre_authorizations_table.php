<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pre_authorizations', function (Blueprint $table) {
            $table->string('coverage_decision')->nullable()->after('case_status');
            $table->date('gop_receiving_date')->nullable()->after('send_out_date');
            $table->string('gop_reference_number')->nullable()->after('gop_receiving_date');
            $table->foreignId('gop_translate_by')->nullable()->after('gop_reference_number')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_authorizations', function (Blueprint $table) {
            $table->dropForeign(['gop_translate_by']);
            $table->dropColumn(['coverage_decision', 'gop_receiving_date', 'gop_reference_number', 'gop_translate_by']);
        });
    }
};
