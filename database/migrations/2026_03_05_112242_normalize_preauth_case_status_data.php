<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pre_authorizations')->where('case_status', 'Data-Entered')->update(['case_status' => 'Data Entered']);
    }

    public function down(): void
    {
        DB::table('pre_authorizations')->where('case_status', 'Data Entered')->update(['case_status' => 'Data-Entered']);
    }
};
