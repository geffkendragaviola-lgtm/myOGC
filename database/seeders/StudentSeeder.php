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
        // 🧍 1st Student (CCS)
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
            'college_id' => 7, // CCS
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

        // 🧍 3rd Student (CASS)
        $user3 = User::create([
            'first_name' => 'cass',
            'middle_name' => 'cass',
            'last_name' => 'cass',
            'birthdate' => '2003-06-11',
            'age' => 21,
            'sex' => 'female',
            'birthplace' => 'Iligan City',
            'religion' => 'Catholic',
            'affiliation' => 'undergraduate',
            'civil_status' => 'single',
            'citizenship' => 'Filipino',
            'address' => 'Iligan City',
            'phone_number' => '09120000001',
            'email' => 'cass@g.msuiit.edu.ph',
            'password' => Hash::make('1234567890'),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user3->id,
            'student_id' => '2023-1001',
            'year_level' => '1st Year',
            'course' => 'Bachelor of Arts in English',
            'college_id' => 1, // CASS
        ]);

        // 🧍 4th Student (COE)
        $user4 = User::create([
            'first_name' => 'coe',
            'middle_name' => 'coe',
            'last_name' => 'coe',
            'birthdate' => '2002-09-15',
            'age' => 22,
            'sex' => 'male',
            'birthplace' => 'Iligan City',
            'religion' => 'Christian',
            'affiliation' => 'undergraduate',
            'civil_status' => 'single',
            'citizenship' => 'Filipino',
            'address' => 'Iligan City',
            'phone_number' => '09120000002',
            'email' => 'coe@g.msuiit.edu.ph',
            'password' => Hash::make('1234567890'),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user4->id,
            'student_id' => '2023-1002',
            'year_level' => '3rd Year',
            'course' => 'Bachelor of Science in Civil Engineering',
            'college_id' => 2, // COE
        ]);

        // 🧍 5th Student (COET)
        $user5 = User::create([
            'first_name' => 'coet',
            'middle_name' => 'coet',
            'last_name' => 'coet',
            'birthdate' => '2003-05-20',
            'age' => 21,
            'sex' => 'male',
            'birthplace' => 'Iligan City',
            'religion' => 'Catholic',
            'affiliation' => 'undergraduate',
            'civil_status' => 'single',
            'citizenship' => 'Filipino',
            'address' => 'Iligan City',
            'phone_number' => '09120000003',
            'email' => 'coet@g.msuiit.edu.ph',
            'password' => Hash::make('1234567890'),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user5->id,
            'student_id' => '2023-1003',
            'year_level' => '2nd Year',
            'course' => 'Bachelor of Science in Industrial Automation Engineering Technology',
            'college_id' => 3, // COET
        ]);

        // 🧍 6th Student (CEBA)
        $user6 = User::create([
            'first_name' => 'ceba',
            'middle_name' => 'ceba',
            'last_name' => 'ceba',
            'birthdate' => '2004-07-18',
            'age' => 20,
            'sex' => 'female',
            'birthplace' => 'Iligan City',
            'religion' => 'Christian',
            'affiliation' => 'undergraduate',
            'civil_status' => 'single',
            'citizenship' => 'Filipino',
            'address' => 'Iligan City',
            'phone_number' => '09120000004',
            'email' => 'ceba@g.msuiit.edu.ph',
            'password' => Hash::make('1234567890'),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user6->id,
            'student_id' => '2023-1004',
            'year_level' => '1st Year',
            'course' => 'Bachelor of Science in Business Administration',
            'college_id' => 4, // CEBA
        ]);

        // 🧍 7th Student (CED)
        $user7 = User::create([
            'first_name' => 'ced',
            'middle_name' => 'ced',
            'last_name' => 'ced',
            'birthdate' => '2003-08-09',
            'age' => 21,
            'sex' => 'female',
            'birthplace' => 'Iligan City',
            'religion' => 'Catholic',
            'affiliation' => 'undergraduate',
            'civil_status' => 'single',
            'citizenship' => 'Filipino',
            'address' => 'Iligan City',
            'phone_number' => '09120000005',
            'email' => 'ced@g.msuiit.edu.ph',
            'password' => Hash::make('1234567890'),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user7->id,
            'student_id' => '2023-1005',
            'year_level' => '4th Year',
            'course' => 'Bachelor of Secondary Education',
            'college_id' => 5, // CED
        ]);

        // 🧍 8th Student (CSM)
        $user8 = User::create([
            'first_name' => 'csm',
            'middle_name' => 'csm',
            'last_name' => 'csm',
            'birthdate' => '2004-02-25',
            'age' => 21,
            'sex' => 'female',
            'birthplace' => 'Iligan City',
            'religion' => 'Christian',
            'affiliation' => 'undergraduate',
            'civil_status' => 'single',
            'citizenship' => 'Filipino',
            'address' => 'Iligan City',
            'phone_number' => '09120000006',
            'email' => 'csm@g.msuiit.edu.ph',
            'password' => Hash::make('1234567890'),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user8->id,
            'student_id' => '2023-1006',
            'year_level' => '2nd Year',
            'course' => 'Bachelor of Science in Biology',
            'college_id' => 6, // CSM
        ]);
    }
}
