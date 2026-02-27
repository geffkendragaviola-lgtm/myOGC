<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->foreignId('target_counselor_id')->nullable()->after('user_id')->constrained('counselors')->nullOnDelete();
            $table->string('personnel_name', 150)->nullable()->after('service_availed');

            $table->boolean('share_mobile')->default(false)->after('is_anonymous');

            $table->string('cc1', 50)->nullable()->after('share_mobile');
            $table->string('cc2', 50)->nullable()->after('cc1');
            $table->string('cc3', 50)->nullable()->after('cc2');

            $table->unsignedTinyInteger('sqd0')->nullable()->after('cc3');
            $table->unsignedTinyInteger('sqd1')->nullable()->after('sqd0');
            $table->unsignedTinyInteger('sqd2')->nullable()->after('sqd1');
            $table->unsignedTinyInteger('sqd3_1')->nullable()->after('sqd2');
            $table->unsignedTinyInteger('sqd3_2')->nullable()->after('sqd3_1');
            $table->unsignedTinyInteger('sqd4')->nullable()->after('sqd3_2');
            $table->unsignedTinyInteger('sqd5')->nullable()->after('sqd4');
            $table->unsignedTinyInteger('sqd6')->nullable()->after('sqd5');
            $table->unsignedTinyInteger('sqd7_1')->nullable()->after('sqd6');
            $table->unsignedTinyInteger('sqd7_2')->nullable()->after('sqd7_1');
            $table->unsignedTinyInteger('sqd7_3')->nullable()->after('sqd7_2');
            $table->unsignedTinyInteger('sqd8')->nullable()->after('sqd7_3');
            $table->unsignedTinyInteger('sqd9')->nullable()->after('sqd8');
        });
    }

    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('target_counselor_id');

            $table->dropColumn([
                'personnel_name',
                'share_mobile',
                'cc1',
                'cc2',
                'cc3',
                'sqd0',
                'sqd1',
                'sqd2',
                'sqd3_1',
                'sqd3_2',
                'sqd4',
                'sqd5',
                'sqd6',
                'sqd7_1',
                'sqd7_2',
                'sqd7_3',
                'sqd8',
                'sqd9',
            ]);
        });
    }
};
