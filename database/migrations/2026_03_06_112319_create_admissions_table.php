
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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('hn');
            $table->string('name')->nullable();
            $table->date('admission_date')->nullable();
            $table->string('room_no')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('procedure_treatment')->nullable();
            $table->foreignId('pre_authorization_id')->nullable()->constrained('pre_authorizations')->nullOnDelete();
            $table->text('additional_note')->nullable();
            $table->string('department')->nullable();
            $table->string('admitting_status')->nullable();
            $table->string('case_status')->nullable();
            $table->date('sent_out_date')->nullable();
            $table->date('initial_gop_receiving_date')->nullable();
            $table->string('gop_pre_certification_status')->nullable();
            $table->string('gop_ref')->nullable();
            $table->date('discharge_date')->nullable();
            $table->dateTime('final_gop')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
