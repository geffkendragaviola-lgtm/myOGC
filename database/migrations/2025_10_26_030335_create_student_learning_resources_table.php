<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_learning_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->enum('internet_access', ['no internet access', 'limited internet access', 'full internet access'])->nullable();
            $table->text('technology_gadgets')->nullable();
            $table->text('internet_connectivity')->nullable();
            $table->enum('distance_learning_readiness', ['fully ready', 'ready', 'a little ready', 'not ready'])->nullable();
            $table->text('learning_space_description')->nullable();
            $table->timestamps();

            $table->unique('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_learning_resources');
    }
};
