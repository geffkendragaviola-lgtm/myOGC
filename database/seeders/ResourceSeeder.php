<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin user ID to associate with resources
        $adminId = DB::table('users')->where('role', 'admin')->value('id');

        if (!$adminId) {
            // If no admin exists, get any user
            $adminId = DB::table('users')->value('id');
        }

        if (!$adminId) {
            // If no users exist at all, create a basic user
            $adminId = DB::table('users')->insertGetId([
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'email' => 'admin@g.msuiit.edu.ph',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $resources = [
            [
                'user_id' => $adminId,
                'title' => 'Free YouTube Videos',
                'description' => 'Curated collection of mental health videos from trusted sources covering various topics.',
                'icon' => 'fab fa-youtube',
                'button_text' => 'Explore Videos',
                'link' => 'https://www.youtube.com/@TED',
                'category' => 'youtube',
                'is_active' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'title' => 'Free eBooks',
                'description' => 'Downloadable books on mental health, self-care, and personal development.',
                'icon' => 'fas fa-book-open',
                'button_text' => 'Browse eBooks',
                 'link' => 'https://www.goodreads.com/shelf/show/mental-health',
                'category' => 'ebooks',
                'is_active' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'title' => 'Private Videos',
                'description' => 'Exclusive video content created by our counseling team for registered students.',
                'icon' => 'fas fa-lock',
                'button_text' => 'Access Content',
                 'link' => 'https://www.youtube.com/@TED',
                'category' => 'private',
                'is_active' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'title' => 'OGC Resources',
                'description' => 'Curated materials, worksheets, and guides developed by our counselors.',
                'icon' => 'fas fa-archive',
                'button_text' => 'View Resources',
                 'link' => 'https://www.goodreads.com/shelf/show/mental-health',
                'category' => 'ogc',
                'is_active' => true,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('resources')->insert($resources);
    }
}
