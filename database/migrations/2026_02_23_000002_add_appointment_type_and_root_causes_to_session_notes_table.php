<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_notes', function (Blueprint $table) {
            if (!Schema::hasColumn('session_notes', 'appointment_type')) {
                $table->string('appointment_type', 50)->nullable()->after('session_type');
            }

            if (!Schema::hasColumn('session_notes', 'root_causes')) {
                $table->json('root_causes')->nullable()->after('follow_up_actions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('session_notes', function (Blueprint $table) {
            if (Schema::hasColumn('session_notes', 'appointment_type')) {
                $table->dropColumn('appointment_type');
            }

            if (Schema::hasColumn('session_notes', 'root_causes')) {
                $table->dropColumn('root_causes');
            }
        });
    }
};
