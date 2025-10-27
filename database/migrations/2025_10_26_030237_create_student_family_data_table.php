<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_family_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('father_name', 100)->nullable();
            $table->boolean('father_deceased')->default(false);
            $table->string('father_occupation', 100)->nullable();
            $table->string('father_phone_number', 20)->nullable();
            $table->string('mother_name', 100)->nullable();
            $table->boolean('mother_deceased')->default(false);
            $table->string('mother_occupation', 100)->nullable();
            $table->string('mother_phone_number', 20)->nullable();
            $table->enum('parents_marital_status', ['married', 'not legally married', 'separated', 'both parents remarried', 'one parent remarried'])->nullable();
            $table->enum('family_monthly_income', ['below 3k', '3001-5000', '5001-8000', '8001-10000', '10001-15000', '15001-20000', '20001 above'])->nullable();
            $table->string('guardian_name', 100)->nullable();
            $table->string('guardian_occupation', 100)->nullable();
            $table->string('guardian_phone_number', 20)->nullable();
            $table->string('guardian_relationship', 50)->nullable();
            $table->enum('ordinal_position', ['only child', 'eldest', 'middle', 'youngest'])->nullable();
            $table->integer('number_of_siblings')->default(0);
            $table->text('home_environment_description')->nullable();
            $table->timestamps();

            $table->unique('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_family_data');
    }
};
