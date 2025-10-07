<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Added this line
            $table->string('title');
            $table->text('description');
            $table->string('type'); // webinar, workshop, seminar, activity
            $table->date('event_start_date');
            $table->date('event_end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->integer('max_attendees')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
