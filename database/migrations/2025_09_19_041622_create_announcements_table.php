    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('title', 255);
                $table->text('content');
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->timestamps();

                // Indexes for better performance
                $table->index(['start_date', 'end_date']);
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('announcements');
        }
    };
