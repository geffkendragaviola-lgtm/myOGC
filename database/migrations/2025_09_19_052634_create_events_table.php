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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('type');
            $table->date('event_start_date');
            $table->date('event_end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->integer('max_attendees')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_required')->default(false); // Add this
            $table->boolean('for_all_colleges')->default(true); // Add this
              $table->string('image')->nullable(); 
            $table->timestamps();
        });

        // Create event_college pivot table
        Schema::create('event_college', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['event_id', 'college_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_college');
        Schema::dropIfExists('events');
    }
};
