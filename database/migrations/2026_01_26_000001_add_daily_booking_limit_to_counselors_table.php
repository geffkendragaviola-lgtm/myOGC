<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            $table->unsignedSmallInteger('daily_booking_limit')->nullable()->after('availability');
        });
    }

    public function down(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            $table->dropColumn('daily_booking_limit');
        });
    }
};
