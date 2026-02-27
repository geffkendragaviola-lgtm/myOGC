<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'referral_outcome')) {
                $table->string('referral_outcome')->nullable()->after('referral_requested_at');
            }
            if (!Schema::hasColumn('appointments', 'referral_resolved_at')) {
                $table->timestamp('referral_resolved_at')->nullable()->after('referral_outcome');
            }
            if (!Schema::hasColumn('appointments', 'referral_resolved_by_counselor_id')) {
                $table->foreignId('referral_resolved_by_counselor_id')
                    ->nullable()
                    ->after('referral_resolved_at')
                    ->constrained('counselors')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'referral_resolved_by_counselor_id')) {
                $table->dropForeign(['referral_resolved_by_counselor_id']);
                $table->dropColumn('referral_resolved_by_counselor_id');
            }
            if (Schema::hasColumn('appointments', 'referral_resolved_at')) {
                $table->dropColumn('referral_resolved_at');
            }
            if (Schema::hasColumn('appointments', 'referral_outcome')) {
                $table->dropColumn('referral_outcome');
            }
        });
    }
};
