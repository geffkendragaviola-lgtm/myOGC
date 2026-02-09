<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'proposed_date')) {
                $table->date('proposed_date')->nullable()->after('appointment_date');
            }
            if (!Schema::hasColumn('appointments', 'proposed_start_time')) {
                $table->time('proposed_start_time')->nullable()->after('start_time');
            }
            if (!Schema::hasColumn('appointments', 'proposed_end_time')) {
                $table->time('proposed_end_time')->nullable()->after('end_time');
            }
            if (!Schema::hasColumn('appointments', 'reschedule_reason')) {
                $table->text('reschedule_reason')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('appointments', 'reschedule_requested_at')) {
                $table->timestamp('reschedule_requested_at')->nullable()->after('reschedule_reason');
            }
        });

        DB::statement('ALTER TABLE appointments DROP CONSTRAINT IF EXISTS appointments_status_check');
        DB::statement("ALTER TABLE appointments ADD CONSTRAINT appointments_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'cancelled', 'completed', 'referred', 'rescheduled', 'reschedule_requested', 'reschedule_rejected'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE appointments DROP CONSTRAINT IF EXISTS appointments_status_check');
        DB::statement("UPDATE appointments SET status = 'approved' WHERE status IN ('rescheduled', 'reschedule_requested', 'reschedule_rejected')");
        DB::statement("ALTER TABLE appointments ADD CONSTRAINT appointments_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'cancelled', 'completed', 'referred', 'rescheduled'))");

        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'reschedule_requested_at')) {
                $table->dropColumn('reschedule_requested_at');
            }
            if (Schema::hasColumn('appointments', 'reschedule_reason')) {
                $table->dropColumn('reschedule_reason');
            }
            if (Schema::hasColumn('appointments', 'proposed_end_time')) {
                $table->dropColumn('proposed_end_time');
            }
            if (Schema::hasColumn('appointments', 'proposed_start_time')) {
                $table->dropColumn('proposed_start_time');
            }
            if (Schema::hasColumn('appointments', 'proposed_date')) {
                $table->dropColumn('proposed_date');
            }
        });
    }
};
