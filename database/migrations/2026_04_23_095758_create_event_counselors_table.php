<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_counselors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained()->onDelete('cascade');
            $table->string('google_calendar_event_id')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'counselor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_counselors');
    }
};
