<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_id', 50)->unique();
            $table->string('year_level', 50);
            $table->string('course', 100);
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->decimal('msu_sase_score', 5, 2)->nullable();
            $table->string('academic_year', 20)->nullable();
            $table->string('profile_picture')->nullable();
            $table->enum('student_status', ['new', 'transferee', 'returnee', 'shiftee'])->default('new');
            $table->timestamps();
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
