<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Foreign key to users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Student-specific fields
            $table->string('student_id', 50)->unique(); // Student ID number
            $table->string('year_level', 50); // e.g., "1st Year", "2nd Year", etc.
            $table->string('course', 100); // Course/program name
            $table->foreignId('college_id')->constrained()->onDelete('cascade');

            $table->timestamps();

            // Ensure one user can only have one student profile
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
