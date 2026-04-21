<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $statuses = [
        'pending',
        'approved',
        'rejected',
        'cancelled',
        'completed',
        'no_show',
        'referred',
        'rescheduled',
        'reschedule_requested',
        'reschedule_rejected',
        'transferred',
    ];

    public function up(): void
    {
        // Drop the old check constraint and re-add with all valid statuses
        DB::statement('ALTER TABLE appointments DROP CONSTRAINT IF EXISTS appointments_status_check');

        $values = implode(', ', array_map(fn($s) => "'$s'", $this->statuses));
        DB::statement("ALTER TABLE appointments ADD CONSTRAINT appointments_status_check CHECK (status IN ($values))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE appointments DROP CONSTRAINT IF EXISTS appointments_status_check');

        $original = "'pending', 'approved', 'rejected', 'cancelled', 'completed'";
        DB::statement("ALTER TABLE appointments ADD CONSTRAINT appointments_status_check CHECK (status IN ($original))");
    }
};
