<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FAQSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin user ID to associate with FAQs
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

        $faqs = [
            [
                'user_id' => $adminId,
                'question' => 'How do I register for mental health events?',
                'answer' => 'You can register for events by clicking the "Register Now" button on the event listing. For some events with limited capacity, you may need to sign in with your student account. If you encounter any issues, please contact the OGC office directly.',
                'category' => 'events',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'question' => 'Are the private resources really confidential?',
                'answer' => 'Yes, all private resources in the Mental Health Corner are only accessible to registered students and are completely confidential. We do not track which specific resources you access, and this information is never shared with faculty or other students.',
                'category' => 'resources',
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'question' => 'Can I suggest topics for future webinars or resources?',
                'answer' => 'Absolutely! We welcome suggestions from students. You can submit your ideas through the Feedback section of our website or by emailing the OGC directly. We regularly review suggestions when planning our events and resource development.',
                'category' => 'general',
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'question' => 'What if I need immediate mental health support?',
                'answer' => 'If you need immediate support, please visit the OGC office during working hours or call our emergency hotline at (063) 221-4050 after hours. For life-threatening emergencies, please call campus security or 911 immediately.',
                'category' => 'support',
                'order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('faqs')->insert($faqs);
    }
}
