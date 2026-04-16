<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_notes', function (Blueprint $table) {
            // Who referred the student to the counselor (e.g. teacher, parent, friend)
            $table->string('referred_by_source')->nullable()->after('appointment_type');
            // Who the counselor referred the student to (e.g. outside mental health professional)
            $table->string('referred_to_destination')->nullable()->after('referred_by_source');
        });
    }

    public function down(): void
    {
        Schema::table('session_notes', function (Blueprint $table) {
            $table->dropColumn(['referred_by_source', 'referred_to_destination']);
        });
    }
};
