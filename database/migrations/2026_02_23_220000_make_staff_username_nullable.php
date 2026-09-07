<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE staff MODIFY username VARCHAR(255) NULL');

        DB::table('staff')
            ->where('role', '!=', 'Receptionist')
            ->update(['username' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('staff')
            ->whereNull('username')
            ->update(['username' => '']);

        DB::statement('ALTER TABLE staff MODIFY username VARCHAR(255) NOT NULL');
    }
};

