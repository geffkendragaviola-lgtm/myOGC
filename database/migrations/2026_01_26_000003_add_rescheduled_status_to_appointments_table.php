<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE appointments DROP CONSTRAINT IF EXISTS appointments_status_check');
        DB::statement("ALTER TABLE appointments ADD CONSTRAINT appointments_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'cancelled', 'completed', 'referred', 'rescheduled'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE appointments DROP CONSTRAINT IF EXISTS appointments_status_check');
        DB::statement("UPDATE appointments SET status = 'approved' WHERE status IN ('rescheduled', 'reschedule_requested', 'reschedule_rejected')");
        DB::statement("ALTER TABLE appointments ADD CONSTRAINT appointments_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'cancelled', 'completed', 'referred'))");
    }
};
