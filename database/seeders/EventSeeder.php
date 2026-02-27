<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $ccsCollegeId = DB::table('colleges')->where('name', 'College of Computer Studies')->value('id');
        $chsCollegeId = DB::table('colleges')->where('name', 'College of Health Sciences')->value('id');
        $cassCollegeId = DB::table('colleges')->where('name', 'College of Arts and Social Sciences')->value('id');

        $ccsCounselorUserId = DB::table('users')->where('email', 'geffkendra.gaviola@g.msuiit.edu.ph')->value('id');
        $cassCounselorUserId = DB::table('users')->where('email', 'ouano@g.msuiit.edu.ph')->value('id');

        $fallbackUserId = DB::table('users')->where('role', 'counselor')->value('id')
            ?? DB::table('users')->where('role', 'admin')->value('id')
            ?? DB::table('users')->value('id');

        $ccsCounselorUserId = $ccsCounselorUserId ?: $fallbackUserId;
        $cassCounselorUserId = $cassCounselorUserId ?: $fallbackUserId;

        $events = [
            [
                'user_id' => $ccsCounselorUserId,
                'title' => 'CCS Career Wellness Talk',
                'description' => 'A wellness and career readiness talk for CCS students.',
                'type' => 'seminar',
                'event_start_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'event_end_date'   => Carbon::now()->addDays(10)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'location' => 'CCS AVR',
                'max_attendees' => 80,
                'is_active' => true,
                'is_required' => false,
                'for_all_colleges' => false,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $ccsCounselorUserId,
                'title' => 'CHS Stress Management Workshop',
                'description' => 'A stress management workshop intended for CHS students.',
                'type' => 'workshop',
                'event_start_date' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'event_end_date'   => Carbon::now()->addDays(14)->format('Y-m-d'),
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'location' => 'CHS Lecture Room',
                'max_attendees' => 60,
                'is_active' => true,
                'is_required' => false,
                'for_all_colleges' => false,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $cassCounselorUserId,
                'title' => 'University Mental Health Webinar',
                'description' => 'A university-wide webinar on mental health and wellness.',
                'type' => 'webinar',
                'event_start_date' => Carbon::now()->addDays(18)->format('Y-m-d'),
                'event_end_date'   => Carbon::now()->addDays(18)->format('Y-m-d'),
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'location' => 'Online (Zoom)',
                'max_attendees' => 150,
                'is_active' => true,
                'is_required' => false,
                'for_all_colleges' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $cassCounselorUserId,
                'title' => 'CASS Student Support Session',
                'description' => 'A student support and coping session for CASS students.',
                'type' => 'activity',
                'event_start_date' => Carbon::now()->addDays(21)->format('Y-m-d'),
                'event_end_date'   => Carbon::now()->addDays(21)->format('Y-m-d'),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'location' => 'CASS Session Room',
                'max_attendees' => 40,
                'is_active' => true,
                'is_required' => false,
                'for_all_colleges' => false,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($events as $eventData) {
            $event = Event::updateOrCreate(
                ['title' => $eventData['title']],
                $eventData
            );

            if ($event->for_all_colleges) {
                $event->colleges()->sync([]);
            } else {
                $targetsByTitle = [
                    'CCS Career Wellness Talk' => [$ccsCollegeId],
                    'CHS Stress Management Workshop' => [$chsCollegeId],
                    'CASS Student Support Session' => [$cassCollegeId],
                ];

                $targetColleges = $targetsByTitle[$event->title] ?? [];
                $targetColleges = array_values(array_filter($targetColleges));
                $event->colleges()->sync($targetColleges);
            }

            if ($event->is_required) {
                $event->registerRequiredStudents();
            }
        }
    }
}
