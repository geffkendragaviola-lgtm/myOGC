<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'referral_previous_status')) {
                $table->string('referral_previous_status')->nullable()->after('referral_reason');
            }
            if (!Schema::hasColumn('appointments', 'referral_requested_at')) {
                $table->timestamp('referral_requested_at')->nullable()->after('referral_previous_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'referral_requested_at')) {
                $table->dropColumn('referral_requested_at');
            }
            if (Schema::hasColumn('appointments', 'referral_previous_status')) {
                $table->dropColumn('referral_previous_status');
            }
        });
    }
};
