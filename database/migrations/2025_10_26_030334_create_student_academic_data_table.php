<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_academic_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('shs_gpa', 4, 2)->nullable();
            $table->boolean('is_scholar')->default(false);
            $table->string('scholarship_type', 100)->nullable();
            $table->string('school_last_attended', 255)->nullable();
            $table->string('school_address', 255)->nullable();
            $table->enum('shs_track', ['academic', 'arts/design', 'tech-voc', 'sports'])->nullable();
            $table->enum('shs_strand', ['GA', 'STEM', 'HUMMS', 'ABM'])->nullable();
            $table->text('awards_honors')->nullable();
            $table->text('student_organizations')->nullable();
            $table->text('co_curricular_activities')->nullable();
            $table->string('career_option_1', 100)->nullable();
            $table->string('career_option_2', 100)->nullable();
            $table->string('career_option_3', 100)->nullable();
            $table->enum('course_choice_by', ['own choice', 'parents choice', 'relative choice', 'sibling choice', 'according to MSU-SASE score/slot', 'others'])->nullable();
            $table->text('course_choice_reason')->nullable();
            $table->text('msu_choice_reasons')->nullable();
            $table->text('future_career_plans')->nullable();
            $table->timestamps();

            $table->unique('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_academic_data');
    }
};
