<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_psychosocial_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->text('personality_characteristics')->nullable();
            $table->text('coping_mechanisms')->nullable();
            $table->text('mental_health_perception')->nullable();
            $table->boolean('had_counseling_before')->default(false);
            $table->boolean('sought_psychologist_help')->default(false);
            $table->text('problem_sharing_targets')->nullable();
            $table->boolean('needs_immediate_counseling')->default(false);
            $table->text('future_counseling_concerns')->nullable();
            $table->timestamps();

            $table->unique('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_psychosocial_data');
    }
};
