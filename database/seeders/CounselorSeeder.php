<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CounselorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert users with complete information
        $users = [
            [
                'first_name' => 'Michael Alain',
                'middle_name' => 'J',
                'last_name' => 'Mamauag',
                'birthdate' => '1995-03-20',
                'age' => 28,
                'sex' => 'male',
                'birthplace' => 'Iligan City',
                'religion' => 'Roman Catholic',

                'civil_status' => 'single',
                'citizenship' => 'Filipino',
                'address' => 'Iligan City, Lanao del Norte',
                'phone_number' => '09123456789',
                'email' => 'mamauag@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'counselor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Charlane',
                'middle_name' => 'N',
                'last_name' => 'Gabutan',
                'birthdate' => '1997-08-15',
                'age' => 26,
                'sex' => 'female',
                'birthplace' => 'Iligan City',
                'religion' => 'Roman Catholic',

                'civil_status' => 'single',
                'citizenship' => 'Filipino',
                'address' => 'Iligan City, Lanao del Norte',
                'phone_number' => '09123456790',
                'email' => 'gabutan@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'counselor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Tessally',
                'middle_name' => 'V',
                'last_name' => 'Saquin',
                'birthdate' => '1994-11-10',
                'age' => 29,
                'sex' => 'female',
                'birthplace' => 'Iligan City',
                'religion' => 'Roman Catholic',

                'civil_status' => 'married',
                'citizenship' => 'Filipino',
                'address' => 'Iligan City, Lanao del Norte',
                'phone_number' => '09123456791',
                'email' => 'saquin@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'counselor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Sittie Aquizah',
                'middle_name' => 'A',
                'last_name' => 'Dumarpa',
                'birthdate' => '1996-06-25',
                'age' => 27,
                'sex' => 'female',
                'birthplace' => 'Marawi City',
                'religion' => 'Islam',

                'civil_status' => 'single',
                'citizenship' => 'Filipino',
                'address' => 'Iligan City, Lanao del Norte',
                'phone_number' => '09123456792',
                'email' => 'dumarpa@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'counselor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Marguerrette Rose',
                'middle_name' => 'M',
                'last_name' => 'Evardone',
                'birthdate' => '1998-12-05',
                'age' => 25,
                'sex' => 'female',
                'birthplace' => 'Cagayan de Oro City',
                'religion' => 'Roman Catholic',

                'civil_status' => 'single',
                'citizenship' => 'Filipino',
                'address' => 'Iligan City, Lanao del Norte',
                'phone_number' => '09123456793',
                'email' => 'evardone@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'counselor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Jeremiah',
                'middle_name' => 'B',
                'last_name' => 'Bagalanon',
                'birthdate' => '1993-09-18',
                'age' => 30,
                'sex' => 'male',
                'birthplace' => 'Iligan City',
                'religion' => 'Protestant',

                'civil_status' => 'married',
                'citizenship' => 'Filipino',
                'address' => 'Iligan City, Lanao del Norte',
                'phone_number' => '09123456794',
                'email' => 'bagalanon@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'counselor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Caryl Jan',
                'middle_name' => 'C',
                'last_name' => 'Encabo',
                'birthdate' => '1999-04-12',
                'age' => 24,
                'sex' => 'female',
                'birthplace' => 'Iligan City',
                'religion' => 'Roman Catholic',
         
                'civil_status' => 'single',
                'citizenship' => 'Filipino',
                'address' => 'Iligan City, Lanao del Norte',
                'phone_number' => '09123456795',
                'email' => 'encabo@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'counselor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert users
        foreach ($users as $user) {
            $userId = DB::table('users')->insertGetId($user);
            $userIds[$user['last_name']] = $userId;
        }

        // Get college IDs
        $colleges = DB::table('colleges')->get()->keyBy('name');

        // Insert counselors with their college assignments
        $counselors = [
            [
                'user_id' => $userIds['Mamauag'],
                'college_id' => $colleges['College of Arts and Social Sciences']->id,
                'position' => 'Guidance Counselor III',
                'credentials' => 'RGC, RPm, LPT',
                'is_head' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds['Gabutan'],
                'college_id' => $colleges['College of Engineering']->id,
                'position' => 'Guidance Counselor III',
                'credentials' => 'RPm',
                'is_head' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds['Saquin'],
                'college_id' => $colleges['College of Engineering Technology']->id,
                'position' => 'Guidance Counselor III',
                'credentials' => 'RGC',
                'is_head' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds['Dumarpa'],
                'college_id' => $colleges['College of Economics, Business and Accountancy']->id,
                'position' => 'Guidance Counselor III',
                'credentials' => 'RGC, LPT',
                'is_head' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds['Evardone'],
                'college_id' => $colleges['College of Education']->id,
                'position' => 'Guidance Services Associate I',
                'credentials' => 'MAEd',
                'is_head' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds['Bagalanon'],
                'college_id' => $colleges['College of Science and Mathematics']->id,
                'position' => 'Guidance Services Associate I',
                'credentials' => 'RPm',
                'is_head' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Caryl Jan C. Encabo - CCS (primary assignment)
            [
                'user_id' => $userIds['Encabo'],
                'college_id' => $colleges['College of Computer Studies']->id,
                'position' => 'Guidance Counselor III',
                'credentials' => 'RGC, LPT',
                'is_head' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Caryl Jan C. Encabo - CHS (secondary assignment)
            [
                'user_id' => $userIds['Encabo'],
                'college_id' => $colleges['College of Health Sciences']->id,
                'position' => 'Guidance Counselor III',
                'credentials' => 'RGC, LPT',
                'is_head' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('counselors')->insert($counselors);
    }
}
