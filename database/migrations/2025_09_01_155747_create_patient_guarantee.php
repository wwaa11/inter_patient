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
        Schema::create('embassies', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('colour')->nullable();
            $table->timestamps();
        });

        Schema::create('guarantee_cases', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("definition")->nullable();
            $table->string('colour')->nullable();
            $table->timestamps();
        });

        Schema::create('patient_additional_types', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('colour')->nullable();
            $table->timestamps();
        });

        Schema::create('patient_main_guarantees', function (Blueprint $table) {
            $table->id();
            $table->string("hn")->references("hn")->on("patients");
            $table->string("embassy");
            $table->string("embassy_ref");
            $table->string("number")->nullable();
            $table->string("mb")->nullable();
            $table->string("issue_date");
            $table->date("cover_start_date");
            $table->date("cover_end_date");
            $table->string("case");
            $table->string("file");
            $table->boolean("extension")->default(false);
            $table->date("extension_cover_end_date")->nullable();
            $table->timestamps();
        });

        Schema::create('patient_additional_headers', function (Blueprint $table) {
            $table->id();
            // Header Section
            $table->string("hn")->references("hn")->on("patients");
            $table->string("type");
            $table->string("embassy_ref");
            $table->string("mb")->nullable();
            // Date Section one type date range or period
            $table->string("issue_date");
            $table->date("cover_start_date")->nullable();
            $table->date("cover_end_date")->nullable();
            // Price Section
            $table->string("total_price")->nullable();
            // File Section
            $table->json("file");
            $table->timestamps();
        });

        Schema::create('patient_additional_details', function (Blueprint $table) {
            // Header Section
            $table->string("guarantee_header_id")->references("id")->on("patient_addtional_headers");
            // Detail Section
            $table->string("case")->nullable();
            $table->string("specific_date")->nullable();
            $table->string("details");
            $table->string("definition")->nullable();
            $table->string("amount")->nullable();
            $table->string("price")->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_additional_details');
        Schema::dropIfExists('patient_additional_headers');
        Schema::dropIfExists('patient_main_guarantees');
        Schema::dropIfExists('patient_additional_types');
        Schema::dropIfExists('guarantee_cases');
        Schema::dropIfExists('embassies');
    }
};
