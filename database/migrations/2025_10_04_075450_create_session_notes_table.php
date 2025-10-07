<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->text('notes');
            $table->text('follow_up_actions')->nullable();
            $table->date('session_date');
            $table->enum('session_type', ['initial', 'follow_up', 'crisis', 'regular'])->default('regular');
            $table->enum('mood_level', ['very_low', 'low', 'neutral', 'good', 'very_good'])->nullable();
            $table->boolean('requires_follow_up')->default(false);
            $table->date('next_session_date')->nullable();
            $table->timestamps();

            // Add index for better performance
            $table->index(['counselor_id', 'student_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_notes');
    }
};
