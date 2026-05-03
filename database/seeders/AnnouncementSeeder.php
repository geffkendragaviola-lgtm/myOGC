<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Announcement;
use App\Models\User;
use App\Models\College;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $counselors = User::where('role', 'counselor')->get();
        $counselor1 = $counselors->first();
        $counselor2 = $counselors->skip(1)->first() ?? $counselor1;
        
        $colleges = College::all();
        if ($colleges->isEmpty() || !$admin || !$counselor1) {
            return;
        }

        $ccs = $colleges->where('name', 'College of Computer Studies')->first() ?? $colleges->first();
        $cass = $colleges->where('name', 'College of Arts and Social Sciences')->first() ?? $colleges->skip(1)->first();
        $chs = $colleges->where('name', 'College of Health Sciences')->first() ?? $colleges->last();

        // Clear existing announcements for a clean seed
        DB::table('announcement_college')->delete();
        Announcement::query()->delete();

        $announcements = [
            // Active, Pinned, All Colleges (Admin)
            [
                'user_id' => $admin->id,
                'title' => 'Welcome to the New Academic Year',
                'content' => 'Welcome students to the new academic year! The guidance and counseling office is open from Monday to Friday, 8AM to 5PM. Feel free to drop by or schedule an appointment online.',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(90),
                'is_active' => true,
                'is_pinned' => true,
                'for_all_colleges' => true,
                'colleges' => [],
            ],
            // Active, specific college (Counselor 1)
            [
                'user_id' => $counselor1->id,
                'title' => 'Mental Health Awareness Month Workshops',
                'content' => 'Join us for a series of workshops focusing on stress management, mindfulness, and healthy coping mechanisms. Open exclusively for CCS students this week.',
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(14),
                'is_active' => true,
                'is_pinned' => false,
                'for_all_colleges' => false,
                'colleges' => [$ccs->id],
            ],
            // Scheduled (Future), specific college (Counselor 2)
            [
                'user_id' => $counselor2->id,
                'title' => 'Midterm Survival Guide Seminar',
                'content' => 'Midterms are approaching! Learn effective study habits and time management skills. Register early as slots are limited.',
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addDays(21),
                'is_active' => true,
                'is_pinned' => false,
                'for_all_colleges' => false,
                'colleges' => [$cass->id],
            ],
            // Completed/Expired (Admin)
            [
                'user_id' => $admin->id,
                'title' => 'Holiday Notice: No Office Hours',
                'content' => 'Please be advised that the Guidance Office will be closed during the national holiday. Normal operations will resume the following Monday.',
                'start_date' => Carbon::now()->subDays(20),
                'end_date' => Carbon::now()->subDays(18),
                'is_active' => false,
                'is_pinned' => false,
                'for_all_colleges' => true,
                'colleges' => [],
            ],
            // Active, Multiple Colleges
            [
                'user_id' => $counselor1->id,
                'title' => 'Career Guidance Orientation',
                'content' => 'Mandatory career guidance orientation for graduating students. Please check your department schedules for your specific time slots.',
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->addDays(10),
                'is_active' => true,
                'is_pinned' => false,
                'for_all_colleges' => false,
                'colleges' => [$ccs->id, $chs->id],
            ],
            // Inactive (Draft)
            [
                'user_id' => $counselor2->id,
                'title' => 'Peer Facilitators Recruitment',
                'content' => 'We are looking for enthusiastic students to join our Peer Facilitators group. More details to follow soon.',
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(30),
                'is_active' => false,
                'is_pinned' => false,
                'for_all_colleges' => true,
                'colleges' => [],
            ],
        ];

        foreach ($announcements as $data) {
            $collegeIds = $data['colleges'];
            unset($data['colleges']);

            $announcement = Announcement::create($data);

            if (!$data['for_all_colleges'] && !empty($collegeIds)) {
                $announcement->colleges()->sync($collegeIds);
            }
        }
    }
}
