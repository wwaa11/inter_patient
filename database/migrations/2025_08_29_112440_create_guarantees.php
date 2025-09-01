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
        Schema::create('guarantee_mains', function (Blueprint $table) {
            $table->id();
            $table->string("hn")->references("hn")->on("patients");
            $table->string("embassy");
            $table->string("number");
            $table->string("issue_date");
            $table->date("cover_start_date");
            $table->date("cover_end_date");
            $table->string("case");
            $table->string("file");
            $table->timestamps();
        });

        // Select Option
        Schema::create('guarantee_main_cases', function (Blueprint $table) {
            $table->id();
            $table->string("case");
            $table->string("case_for_staff")->nullable();
            $table->timestamps();
        });

        Schema::create('guarantee_addtional', function (Blueprint $table) {
            $table->id();
            $table->string("hn")->references("hn")->on("patients");
            $table->string("embassy_ref");
            $table->string("issue_date");
            $table->date("cover_start_date");
            $table->date("cover_end_date");
            $table->string("details");
            $table->string("details_for_staff")->nullable();
            $table->string("file");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantee_mains');
        Schema::dropIfExists('guarantee_main_cases');
        Schema::dropIfExists('guarantee_addtional');
    }
};
