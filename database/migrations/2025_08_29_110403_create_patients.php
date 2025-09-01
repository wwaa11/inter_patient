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
        Schema::create('patients', function (Blueprint $table) {
            $table->string("hn")->primary();
            $table->string("name")->nullable();
            $table->string("gender")->nullable();
            $table->string("birthday")->nullable();
            $table->string("qid")->nullable();
            $table->string("nationality")->nullable();
            $table->string("type")->nullable(); // OPD, IPD
            $table->string("location")->nullable();
            $table->timestamps();
        });

        Schema::create('patient_notes', function (Blueprint $table) {
            $table->id();
            $table->string("hn")->references("hn")->on("patients");
            $table->string("note");
            $table->timestamps();
        });

        Schema::create('patient_passports', function (Blueprint $table) {
            $table->id();
            $table->string("hn")->references("hn")->on("patients");
            $table->string("file");
            $table->string("number")->nullable();
            $table->string("issue_date")->nullable();
            $table->string("expiry_date")->nullable();
            $table->timestamps();
        });

        Schema::create('patient_medical_records', function (Blueprint $table) {
            $table->id();
            $table->string("hn")->references("hn")->on("patients");
            $table->date("date");
            $table->string("file");
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
        Schema::dropIfExists('patient_passports');
        Schema::dropIfExists('patient_medical_records');
        Schema::dropIfExists('patient_notes');
    }
};
