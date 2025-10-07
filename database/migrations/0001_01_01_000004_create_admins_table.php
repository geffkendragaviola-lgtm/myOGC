<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('credentials', 100);
            $table->timestamps();

            // Ensure one user can only have one admin profile
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
