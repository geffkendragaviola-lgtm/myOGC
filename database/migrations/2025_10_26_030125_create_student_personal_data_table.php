<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_personal_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('nickname', 100)->nullable();
            $table->text('home_address')->nullable();
            $table->enum('stays_with', ['parents/guardian', 'board/roommates', 'relatives', 'friends', 'employer', 'living on my own'])->nullable();
// In your student_personal_data migration, make sure the enum values match:
$table->enum('working_student', [
    'yes full time',
    'yes part time',
    'no but planning to work',
    'no and have no plan to work'  // Make sure this exactly matches what you're inserting
])->nullable();
            $table->text('talents_skills')->nullable();
            $table->text('leisure_activities')->nullable();
            $table->string('serious_medical_condition', 255)->nullable();
            $table->string('physical_disability', 255)->nullable();
            $table->enum('gender_identity', ['male/man', 'female/woman', 'transgender male/man', 'transgender female/woman', 'gender variant/nonconforming', 'not listed', 'prefer not to say'])->nullable();
            $table->enum('romantic_attraction', ['my same gender', 'opposite gender', 'both men and women', 'all genders', 'neither gender', 'prefer not to answer'])->nullable();
            $table->timestamps();

            $table->unique('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_personal_data');
    }
};
