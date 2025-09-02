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
        Schema::table('embassies', function (Blueprint $table) {
            $table->string('color')->nullable();
        });

        Schema::table('guarantee_main_cases', function (Blueprint $table) {
            $table->string('color')->nullable();
        });

        Schema::table('guarantee_addtional_cases', function (Blueprint $table) {
            $table->string('color')->nullable();
        });

        Schema::table('patient_addtional_types', function (Blueprint $table) {
            $table->string('color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('embassies', function (Blueprint $table) {
            $table->dropColumn('color');
        });

        Schema::table('guarantee_main_cases', function (Blueprint $table) {
            $table->dropColumn('color');
        });

        Schema::table('guarantee_addtional_cases', function (Blueprint $table) {
            $table->dropColumn('color');
        });

        Schema::table('patient_addtional_types', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
