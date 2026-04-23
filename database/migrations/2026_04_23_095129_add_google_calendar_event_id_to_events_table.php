<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'google_calendar_event_id')) {
                $table->string('google_calendar_event_id')->nullable()->after('image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'google_calendar_event_id')) {
                $table->dropColumn('google_calendar_event_id');
            }
        });
    }
};
