<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admission_notes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('pre_authorization_notes', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('admission_notes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pre_authorization_notes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
