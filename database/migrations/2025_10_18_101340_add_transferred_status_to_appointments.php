<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove the existing check constraint
        DB::statement('ALTER TABLE appointments DROP CONSTRAINT IF EXISTS appointments_status_check');

        // Add new check constraint with only 'referred' status (no 'transferred')
        DB::statement("ALTER TABLE appointments ADD CONSTRAINT appointments_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'cancelled', 'completed', 'referred'))");
    }

    public function down(): void
    {
        // Remove the new check constraint
        DB::statement('ALTER TABLE appointments DROP CONSTRAINT IF EXISTS appointments_status_check');

        // Restore original check constraint
        DB::statement("ALTER TABLE appointments ADD CONSTRAINT appointments_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'cancelled', 'completed'))");
    }
};
