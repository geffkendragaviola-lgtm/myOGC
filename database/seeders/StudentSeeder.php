<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🧍 1st Student (Original)
        $user1 = User::create([
            'first_name' => 'Geff Kendra',
            'middle_name' => 'Calumpag',
            'last_name' => 'Gaviola',
            'birthdate' => '2003-12-14',
            'age' => 21,
            'sex' => 'female',
            'birthplace' => 'Iligan City',
            'religion' => 'SDA',
            'affiliation' => 'undergraduate',
            'civil_status' => 'single',
            'citizenship' => 'Filipino',
            'address' => 'Iligan City',
            'phone_number' => '09952796162',
            'email' => 'student@g.msuiit.edu.ph',
            'password' => Hash::make('1234567890'),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user1->id,
            'student_id' => '2022-2622',
            'year_level' => '1st Year',
            'course' => 'Bachelor of Science in Computer Science',
            'college_id' => 7, // Make sure this exists
        ]);

        // 🧍‍♀️ 2nd Student (CHS)
        $user2 = User::create([
            'first_name' => 'chs',
            'middle_name' => 'chs',
            'last_name' => 'chs',
            'birthdate' => '2004-03-22',
            'age' => 20,
            'sex' => 'female',
            'birthplace' => 'Cagayan de Oro City',
            'religion' => 'Catholic',
            'affiliation' => 'undergraduate',
            'civil_status' => 'single',
            'citizenship' => 'Filipino',
            'address' => 'Cagayan de Oro City',
            'phone_number' => '09123456789',
            'email' => 'chs@g.msuiit.edu.ph',
            'password' => Hash::make('1234567890'),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user2->id,
            'student_id' => '2023-1088',
            'year_level' => '2nd Year',
            'course' => 'Bachelor of Science in Nursing',
            'college_id' => 8, // CHS
        ]);
    }
}
