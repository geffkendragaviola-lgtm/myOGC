<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            if (!Schema::hasColumn('resources', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false)->after('is_active');
            }
        });

        Schema::table('faqs', function (Blueprint $table) {
            if (!Schema::hasColumn('faqs', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('is_pinned');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn('is_pinned');
        });
    }
};
