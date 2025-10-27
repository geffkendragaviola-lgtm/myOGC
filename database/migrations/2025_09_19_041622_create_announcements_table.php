<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 255);
            $table->text('content');
            $table->string('image')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('for_all_colleges')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index(['start_date', 'end_date']);
            $table->index(['is_active', 'for_all_colleges']);
        });

        // Pivot table for announcement colleges
        Schema::create('announcement_college', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->onDelete('cascade');
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['announcement_id', 'college_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_college');
        Schema::dropIfExists('announcements');
    }
};
