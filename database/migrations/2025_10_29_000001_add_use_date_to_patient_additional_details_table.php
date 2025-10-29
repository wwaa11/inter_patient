<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_additional_details', function (Blueprint $table) {
            if (! Schema::hasColumn('patient_additional_details', 'use_date')) {
                $table->date('use_date')->nullable()->after('end_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patient_additional_details', function (Blueprint $table) {
            if (Schema::hasColumn('patient_additional_details', 'use_date')) {
                $table->dropColumn('use_date');
            }
        });
    }
};