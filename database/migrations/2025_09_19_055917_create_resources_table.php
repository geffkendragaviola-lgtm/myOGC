<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('icon'); // Font Awesome icon class
            $table->string('button_text');
            $table->string('link')->nullable();
            $table->string('category'); // youtube, ebooks, private, ogc
            $table->string('image_path')->nullable(); // Custom uploaded image
            $table->boolean('use_yt_thumbnail')->default(false); // Use YT thumbnail instead of custom image
            $table->boolean('show_disclaimer')->default(false); // Show disclaimer for this resource
            $table->text('disclaimer_text')->nullable(); // Custom disclaimer text
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
