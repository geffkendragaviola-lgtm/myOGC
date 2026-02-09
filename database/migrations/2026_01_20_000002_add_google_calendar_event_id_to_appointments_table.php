<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'google_calendar_event_id')) {
                $table->string('google_calendar_event_id')->nullable()->after('original_counselor_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'google_calendar_event_id')) {
                $table->dropColumn('google_calendar_event_id');
            }
        });
    }
};
