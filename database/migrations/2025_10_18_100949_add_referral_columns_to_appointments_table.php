<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('appointments', 'referred_to_counselor_id')) {
                $table->foreignId('referred_to_counselor_id')->nullable()->constrained('counselors')->onDelete('set null');
            }

            if (!Schema::hasColumn('appointments', 'original_counselor_id')) {
                $table->foreignId('original_counselor_id')->nullable()->constrained('counselors')->onDelete('set null');
            }

            if (!Schema::hasColumn('appointments', 'referral_reason')) {
                $table->text('referral_reason')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Only drop columns if they exist
            if (Schema::hasColumn('appointments', 'referred_to_counselor_id')) {
                $table->dropForeign(['referred_to_counselor_id']);
                $table->dropColumn('referred_to_counselor_id');
            }

            if (Schema::hasColumn('appointments', 'original_counselor_id')) {
                $table->dropForeign(['original_counselor_id']);
                $table->dropColumn('original_counselor_id');
            }

            if (Schema::hasColumn('appointments', 'referral_reason')) {
                $table->dropColumn('referral_reason');
            }
        });
    }
};
