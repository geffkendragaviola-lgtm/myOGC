<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (!Schema::hasColumn('announcements', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false)->after('is_active');
            }
        });

        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('is_pinned');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('is_pinned');
        });
    }
};
