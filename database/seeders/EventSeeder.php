<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin user ID to associate with events
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

        $events = [
            [
                'user_id' => $adminId,
                'title' => 'Managing Anxiety in College',
                'description' => 'Learn practical strategies to manage anxiety and stress during your college years from our expert counselors.',
                'type' => 'Webinar',
                'event_start_date' => Carbon::now()->addDays(22)->format('Y-m-d'),
                'event_end_date'   => Carbon::now()->addDays(24)->format('Y-m-d'), // 3-day event
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'location' => 'Online (Zoom)',
                'max_attendees' => 100,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'title' => 'Art Therapy Session with Mx. Guidance Counselor',
                'description' => 'Express yourself through art in this guided therapeutic session. Materials provided. Limited slots available.',
                'type' => 'Activity',
                'event_start_date' => Carbon::now()->addDays(25)->format('Y-m-d'),
                'event_end_date'   => Carbon::now()->addDays(27)->format('Y-m-d'), // 3-day event
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'location' => 'OGC Activity Room',
                'max_attendees' => 20,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'title' => 'Building Resilience in Challenging Times',
                'description' => 'Discover techniques to build emotional resilience and cope with academic and personal challenges.',
                'type' => 'Seminar',
                'event_start_date' => Carbon::now()->addDays(28)->format('Y-m-d'),
                'event_end_date'   => Carbon::now()->addDays(30)->format('Y-m-d'), // 3-day event
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'location' => 'University Auditorium',
                'max_attendees' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('events')->insert($events);
    }
}
