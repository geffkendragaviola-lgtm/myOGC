<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselor_schedule_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counselor_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->boolean('is_closed')->default(false);
            $table->json('time_slots')->nullable();
            $table->timestamps();

            $table->unique(['counselor_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselor_schedule_overrides');
    }
};
