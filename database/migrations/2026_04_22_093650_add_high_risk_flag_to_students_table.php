<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('is_high_risk')->default(false)->after('initial_interview_completed');
            $table->text('high_risk_notes')->nullable()->after('is_high_risk');
            $table->timestamp('high_risk_flagged_at')->nullable()->after('high_risk_notes');
            $table->unsignedBigInteger('high_risk_flagged_by')->nullable()->after('high_risk_flagged_at');
            
            $table->foreign('high_risk_flagged_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['high_risk_flagged_by']);
            $table->dropColumn(['is_high_risk', 'high_risk_notes', 'high_risk_flagged_at', 'high_risk_flagged_by']);
        });
    }
};
