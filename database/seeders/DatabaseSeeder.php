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
            AdminSeeder::class,
            CounselorSeeder::class,
            CounselorAvailabilitySeeder::class,
               StudentSeeder::class,
            AnnouncementSeeder::class,
          ServiceSeeder::class,
             EventSeeder::class,
      
                FAQSeeder::class,
            ResourceSeeder::class,
            YoutubeResourceSeeder::class,
            EbookResourceSeeder::class,
            OgcResourceSeeder::class,
        ]);
    }
}
