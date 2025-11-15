<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Resource;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CollegeSeeder::class,
            CounselorSeeder::class,
               StudentSeeder::class,
            AnnouncementSeeder::class,
          ServiceSeeder::class,
             EventSeeder::class,
      
                FAQSeeder::class,


        ]);
    }
}
