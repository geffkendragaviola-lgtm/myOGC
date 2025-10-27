<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_needs_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->text('improvement_needs')->nullable();
            $table->text('financial_assistance_needs')->nullable();
            $table->text('personal_social_needs')->nullable();
            $table->text('stress_responses')->nullable();
            $table->enum('easy_discussion_target', ['guidance counselor', 'parents', 'teachers', 'brothers/sisters', 'friends/relatives', 'nobody', 'others'])->nullable();
            $table->text('counseling_perceptions')->nullable();
            $table->timestamps();

            $table->unique('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_needs_assessments');
    }
};
