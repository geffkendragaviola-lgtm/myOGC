<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_needs_assessments', function (Blueprint $table) {
            $table->text('easy_discussion_other')->nullable()->after('easy_discussion_target');
        });
    }

    public function down(): void
    {
        Schema::table('student_needs_assessments', function (Blueprint $table) {
            $table->dropColumn('easy_discussion_other');
        });
    }
};
