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
        Schema::create('pre_authorizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_type_id')->constrained('service_types')->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('providers')->cascadeOnDelete();
            $table->string('hn');
            $table->string('patient_name')->nullable();
            $table->date('date_of_service')->nullable();
            $table->text('operations_procedures')->nullable();
            $table->foreignId('notifier_id')->nullable()->constrained('notifiers')->nullOnDelete();
            $table->dateTime('requested_date')->nullable();
            $table->string('case_status')->default('Data-Entered');
            $table->date('send_out_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_authorizations');
    }
};
