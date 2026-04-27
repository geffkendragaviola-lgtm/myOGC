<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('is_appointment_high_risk')->default(false)->after('mood_rating');
            $table->text('appointment_high_risk_notes')->nullable()->after('is_appointment_high_risk');
            $table->boolean('appointment_high_risk_counselor_flagged')->default(false)->after('appointment_high_risk_notes');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'is_appointment_high_risk',
                'appointment_high_risk_notes',
                'appointment_high_risk_counselor_flagged',
            ]);
        });
    }
};
