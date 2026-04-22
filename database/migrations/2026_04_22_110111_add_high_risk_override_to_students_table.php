<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // true = counselor explicitly cleared the flag, overriding assessment logic
            $table->boolean('high_risk_overridden')->default(false)->after('high_risk_flagged_by');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('high_risk_overridden');
        });
    }
};
