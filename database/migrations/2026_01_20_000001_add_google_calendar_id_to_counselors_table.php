<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            if (!Schema::hasColumn('counselors', 'google_calendar_id')) {
                $table->string('google_calendar_id')->nullable()->after('availability');
            }
        });
    }

    public function down(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            if (Schema::hasColumn('counselors', 'google_calendar_id')) {
                $table->dropColumn('google_calendar_id');
            }
        });
    }
};
