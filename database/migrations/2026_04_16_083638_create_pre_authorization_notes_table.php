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
        Schema::create('pre_authorization_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_authorization_id')->constrained('pre_authorizations')->cascadeOnDelete();
            $table->text('note');
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_authorization_notes');
    }
};
