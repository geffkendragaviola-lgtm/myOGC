<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CounselorAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $defaultSlots = ['08:00-12:00', '13:00-17:00'];

        $availabilityForDays = function (array $workingDays) use ($defaultSlots): array {
            $days = [
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday',
            ];

            $availability = [];
            foreach ($days as $day) {
                $availability[$day] = in_array($day, $workingDays, true) ? $defaultSlots : [];
            }

            return $availability;
        };

        $monFri = $availabilityForDays(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
        $tueThu = $availabilityForDays(['tuesday', 'thursday']);
        $mwf = $availabilityForDays(['monday', 'wednesday', 'friday']);

        DB::table('counselors')->update([
            'daily_booking_limit' => 3,
            'availability' => json_encode($monFri),
            'updated_at' => now(),
        ]);

        $carylUserId = DB::table('users')->where('role', 'counselor')->where('first_name', 'Caryl Jan')->value('id');
        if ($carylUserId) {
            DB::table('counselors')->where('user_id', $carylUserId)->update([
                'daily_booking_limit' => 3,
                'availability' => json_encode($tueThu),
                'updated_at' => now(),
            ]);
        }

        $michaelUserId = DB::table('users')->where('role', 'counselor')->where('first_name', 'Michael Alain')->value('id');
        if ($michaelUserId) {
            DB::table('counselors')->where('user_id', $michaelUserId)->update([
                'daily_booking_limit' => 3,
                'availability' => json_encode($mwf),
                'updated_at' => now(),
            ]);
        }

        $charleneUserId = DB::table('users')->where('role', 'counselor')->whereIn('first_name', ['Charlene', 'Charlane'])->value('id');
        if ($charleneUserId) {
            DB::table('counselors')->where('user_id', $charleneUserId)->update([
                'daily_booking_limit' => 3,
                'availability' => json_encode($mwf),
                'updated_at' => now(),
            ]);
        }
    }
}
