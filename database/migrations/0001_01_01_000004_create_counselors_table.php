<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->string('position', 100);
            $table->string('credentials', 100);
            $table->boolean('is_head')->default(false);
            $table->string('specialization', 100)->nullable();
            $table->json('availability')->nullable(); // Add this line
            $table->timestamps();

            // Add unique constraint
            $table->unique(['user_id', 'college_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselors');
    }
};
