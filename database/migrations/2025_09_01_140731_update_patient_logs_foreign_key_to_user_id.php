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
        // First, add unique constraint to userid column in users table
        Schema::table('users', function (Blueprint $table) {
            $table->unique('userid');
        });
        
        // Then update the patient_logs table
        Schema::table('patient_logs', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['action_by']);
            
            // Change the column type from unsignedBigInteger to string
            $table->string('action_by')->change();
            
            // Create new foreign key to reference userid
            $table->foreign('action_by')->references('userid')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_logs', function (Blueprint $table) {
            // Drop the updated foreign key constraint
            $table->dropForeign(['action_by']);
            
            // Change the column type back to unsignedBigInteger
            $table->unsignedBigInteger('action_by')->change();
            
            // Restore the original foreign key to reference id
            $table->foreign('action_by')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Remove unique constraint from userid column
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['userid']);
        });
    }
};
