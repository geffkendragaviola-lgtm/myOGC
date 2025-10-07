<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin user ID to associate with announcements
        $adminId = DB::table('users')->where('role', 'admin')->value('id');

        if (!$adminId) {
            // If no admin exists, create one
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

        $announcements = [
            [
                'user_id' => $adminId,
                'title' => 'New Counseling Services Available',
                'content' => "We're excited to announce new mental health services available to all students starting next month. Check our updated service catalog for more information.\n\nServices include:\n- Individual counseling sessions\n- Group therapy workshops\n- Career guidance consultations\n- Stress management programs",
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'title' => 'Summer Session Hours',
                'content' => "Our summer session hours are now in effect. The office will be open from 8:00 AM to 4:00 PM, Monday through Friday.\n\nPlease note:\n- Walk-in consultations are available\n- Appointments are recommended for specialized services\n- Emergency services remain available 24/7",
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->addDays(60),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'title' => 'Workshop on Stress Management',
                'content' => "Join us for a free workshop on stress management techniques on June 25th. Learn practical strategies to manage academic pressure.\n\nWorkshop details:\n- Date: June 25, 2023\n- Time: 2:00 PM - 4:00 PM\n- Location: Guidance Office Conference Room\n- Facilitator: Dr. Jane Smith",
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(15),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'title' => 'Mental Health Awareness Month',
                'content' => "May is Mental Health Awareness Month! Join us for various activities and seminars throughout the month.\n\nUpcoming events:\n- Seminar: Understanding Anxiety (May 15)\n- Workshop: Mindfulness Meditation (May 22)\n- Group Discussion: Building Resilience (May 29)",
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('announcements')->insert($announcements);
    }
}
