<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Announcement;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $ccsCollegeId = DB::table('colleges')->where('name', 'College of Computer Studies')->value('id');
        $chsCollegeId = DB::table('colleges')->where('name', 'College of Health Sciences')->value('id');
        $cassCollegeId = DB::table('colleges')->where('name', 'College of Arts and Social Sciences')->value('id');

        $ccsCounselorUserId = DB::table('users')->where('email', 'geffkendra.gaviola@g.msuiit.edu.ph')->value('id');
        $cassCounselorUserId = DB::table('users')->where('email', 'ouano@g.msuiit.edu.ph')->value('id');

        if (!$ccsCounselorUserId || !$cassCounselorUserId) {
            return;
        }

        $announcements = [
            [
                'user_id' => $ccsCounselorUserId,
                'title' => 'CCS Counseling Advisory',
                'content' => "CCS students are invited to schedule counseling appointments for academic and personal concerns.",
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->addDays(30),
                'is_active' => true,
                'for_all_colleges' => false,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $ccsCounselorUserId,
                'title' => 'CHS Counseling Advisory',
                'content' => "CHS students may visit the guidance office for counseling support and wellness check-ins.",
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(30),
                'is_active' => true,
                'for_all_colleges' => false,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $cassCounselorUserId,
                'title' => 'University Guidance Announcement',
                'content' => "Guidance services are available to all colleges. Please check counselor schedules and set appointments as needed.",
                'start_date' => Carbon::now()->subDays(3),
                'end_date' => Carbon::now()->addDays(45),
                'is_active' => true,
                'for_all_colleges' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $cassCounselorUserId,
                'title' => 'CASS Student Wellness Update',
                'content' => "CASS students: wellness resources and support sessions are available this month. Watch for schedules and sign-up links.",
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(25),
                'is_active' => true,
                'for_all_colleges' => false,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($announcements as $announcementData) {
            $announcement = Announcement::updateOrCreate(
                ['title' => $announcementData['title']],
                $announcementData
            );

            if ($announcement->for_all_colleges) {
                $announcement->colleges()->sync([]);
            } else {
                $targetsByTitle = [
                    'CCS Counseling Advisory' => [$ccsCollegeId],
                    'CHS Counseling Advisory' => [$chsCollegeId],
                    'CASS Student Wellness Update' => [$cassCollegeId],
                ];

                $targetColleges = $targetsByTitle[$announcement->title] ?? [];
                $targetColleges = array_values(array_filter($targetColleges));
                $announcement->colleges()->sync($targetColleges);
            }
        }
    }
}
