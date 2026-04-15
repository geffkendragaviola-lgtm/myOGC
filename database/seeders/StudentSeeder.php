<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\StudentPersonalData;
use App\Models\StudentFamilyData;
use App\Models\StudentAcademicData;
use App\Models\StudentLearningResources;
use App\Models\StudentPsychosocialData;
use App\Models\StudentNeedsAssessment;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedStudent(
            userData: [
                'first_name' => 'Geff Kendra',
                'middle_name' => 'Calumpag',
                'last_name' => 'Gaviola',
                'birthdate' => '2003-12-14',
                'age' => 21,
                'sex' => 'female',
                'birthplace' => 'Iligan City',
                'religion' => 'SDA',
                'civil_status' => 'single',
                'number_of_children' => 0,
                'citizenship' => 'Filipino',
                'address' => 'Iligan City',
                'phone_number' => '09952796162',
                'email' => 'geff.gaviola@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
            ],
            studentData: [
                'student_id' => '2022-2622',
                'year_level' => '1st Year',
                'course' => 'Bachelor of Science in Computer Science',
                'college_id' => 7,
                'msu_sase_score' => 85.5,
                'academic_year' => '2024-2025',
                'student_status' => 'new',
            ],
            relations: [
                'personal' => [
                    'nickname' => 'Geff',
                    'home_address' => 'Tibanga, Iligan City',
                    'stays_with' => 'parents/guardian',
                    'working_student' => 'no and have no plan to work',
                    'talents_skills' => json_encode(['programming', 'web development', 'problem solving']),
                    'leisure_activities' => json_encode(['reading', 'coding', 'gaming']),
                    'serious_medical_condition' => 'None',
                    'physical_disability' => 'None',
                    'sex_identity' => 'female/woman',
                    'romantic_attraction' => 'opposite sex',
                ],
                'family' => [
                    'father_name' => 'Juan Gaviola',
                    'father_deceased' => false,
                    'father_occupation' => 'Engineer',
                    'father_phone_number' => '09123456789',
                    'mother_name' => 'Maria Gaviola',
                    'mother_deceased' => false,
                    'mother_occupation' => 'Teacher',
                    'mother_phone_number' => '09198765432',
                    'parents_marital_status' => 'married',
                    'family_monthly_income' => '15001-20000',
                    'guardian_name' => null,
                    'guardian_occupation' => null,
                    'guardian_phone_number' => null,
                    'guardian_relationship' => null,
                    'ordinal_position' => 'eldest',
                    'number_of_siblings' => 2,
                    'home_environment_description' => 'Supportive and nurturing family environment',
                ],
                'academic' => [
                    'shs_gpa' => 92.5,
                    'is_scholar' => true,
                    'scholarship_type' => 'Academic Scholar',
                    'school_last_attended' => 'Iligan City National High School',
                    'school_address' => 'Iligan City',
                    'shs_track' => 'academic',
                    'shs_strand' => 'STEM',
                    'awards_honors' => json_encode(['Valedictorian', 'Best in Math']),
                    'student_organizations' => json_encode(['Math Club', 'Programming Club']),
                    'co_curricular_activities' => json_encode(['Robotics Competition', 'Math Olympiad']),
                    'career_option_1' => 'Software Developer',
                    'career_option_2' => 'Data Scientist',
                    'career_option_3' => 'Web Developer',
                    'course_choice_by' => 'own choice',
                    'course_choice_reason' => 'Passion for technology and programming',
                    'msu_choice_reasons' => json_encode(['quality education', 'affordable tuition fees', 'prestigious institution']),
                    'future_career_plans' => 'Become a software engineer and work in tech industry',
                ],
                'learning_resources' => [
                    'internet_access' => 'full internet access',
                    'technology_gadgets' => json_encode(['laptop/notebook', 'mobile phone smartphone']),
                    'internet_connectivity' => json_encode(['home internet']),
                    'distance_learning_readiness' => 'fully ready',
                    'learning_space_description' => 'Quiet room with desk and computer',
                ],
                'psychosocial' => [
                    'personality_characteristics' => json_encode(['organized', 'analytical', 'introverted']),
                    'coping_mechanisms' => json_encode(['listening to music', 'talking to friends']),
                    'mental_health_perception' => 'Generally good, occasional stress',
                    'had_counseling_before' => false,
                    'sought_psychologist_help' => false,
                    'problem_sharing_targets' => json_encode(['friends', 'mother']),
                    'needs_immediate_counseling' => false,
                    'future_counseling_concerns' => 'Academic pressure and career decisions',
                ],
                'needs_assessment' => [
                    'improvement_needs' => json_encode(['Time-management skills', 'Test-taking skills']),
                    'financial_assistance_needs' => json_encode(['Grants/scholarships']),
                    'personal_social_needs' => json_encode(['Stress management', 'Motivation']),
                    'stress_responses' => json_encode(['Listened to music', 'Tried to solve my problem']),
                    'easy_discussion_target' => 'friends/relatives',
                    'counseling_perceptions' => json_encode([
                        'I know that help is available at the Guidance and Counseling Center of MSU-IIT.' => 'always',
                        'I am shy to ask assistance/seek counseling from my guidance counselor.' => 'sometimes',
                    ]),
                ],
            ]
        );

        $this->seedStudent(
            userData: [
                'first_name' => 'Maria',
                'middle_name' => 'Santos',
                'last_name' => 'Reyes',
                'birthdate' => '2004-03-22',
                'age' => 20,
                'sex' => 'female',
                'birthplace' => 'Cagayan de Oro City',
                'religion' => 'Catholic',
                'civil_status' => 'single',
                'number_of_children' => 0,
                'citizenship' => 'Filipino',
                'address' => 'Cagayan de Oro City',
                'phone_number' => '09120000009',
                'email' => 'chs@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
            ],
            studentData: [
                'student_id' => '2023-1088',
                'year_level' => '2nd Year',
                'course' => 'Bachelor of Science in Nursing',
                'college_id' => 8,
                'msu_sase_score' => 88.2,
                'academic_year' => '2024-2025',
                'student_status' => 'new',
            ],
            relations: [
                'personal' => [
                    'nickname' => 'Mari',
                    'home_address' => 'Cagayan de Oro City',
                    'stays_with' => 'relatives',
                    'working_student' => 'no but planning to work',
                    'talents_skills' => json_encode(['caregiving', 'communication', 'first aid']),
                    'leisure_activities' => json_encode(['reading medical journals', 'volunteering']),
                    'serious_medical_condition' => 'None',
                    'physical_disability' => 'None',
                    'sex_identity' => 'female/woman',
                    'romantic_attraction' => 'opposite sex',
                ],
            ]
        );

        $this->seedStudent(
            userData: [
                'first_name' => 'Juan',
                'middle_name' => 'Dela Cruz',
                'last_name' => 'Santos',
                'birthdate' => '2003-06-11',
                'age' => 21,
                'sex' => 'male',
                'birthplace' => 'Iligan City',
                'religion' => 'Catholic',
                'civil_status' => 'single',
                'number_of_children' => 0,
                'citizenship' => 'Filipino',
                'address' => 'Iligan City',
                'phone_number' => '09120000001',
                'email' => 'cass@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
            ],
            studentData: [
                'student_id' => '2023-1001',
                'year_level' => '1st Year',
                'course' => 'Bachelor of Arts in English',
                'college_id' => 1,
                'msu_sase_score' => 82.7,
                'academic_year' => '2024-2025',
                'student_status' => 'new',
            ],
            relations: [
                'personal' => [
                    'nickname' => 'John',
                    'home_address' => 'Palao, Iligan City',
                    'stays_with' => 'board/roommates',
                    'working_student' => 'yes part time',
                    'talents_skills' => json_encode(['writing', 'public speaking', 'research']),
                    'leisure_activities' => json_encode(['reading novels', 'debating']),
                    'serious_medical_condition' => 'None',
                    'physical_disability' => 'None',
                    'sex_identity' => 'male/man',
                    'romantic_attraction' => 'both men and women',
                ],
            ]
        );

        $this->seedStudent(
            userData: [
                'first_name' => 'Carlos',
                'middle_name' => 'Miguel',
                'last_name' => 'Gonzales',
                'birthdate' => '2002-09-15',
                'age' => 22,
                'sex' => 'male',
                'birthplace' => 'Iligan City',
                'religion' => 'Christian',
                'civil_status' => 'single',
                'number_of_children' => 0,
                'citizenship' => 'Filipino',
                'address' => 'Iligan City',
                'phone_number' => '09120000002',
                'email' => 'coe@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
            ],
            studentData: [
                'student_id' => '2023-1002',
                'year_level' => '3rd Year',
                'course' => 'Bachelor of Science in Civil Engineering',
                'college_id' => 2,
                'msu_sase_score' => 87.9,
                'academic_year' => '2024-2025',
                'student_status' => 'returnee',
            ],
            relations: [
                'personal' => [
                    'nickname' => 'Caloy',
                    'home_address' => 'Tominobo, Iligan City',
                    'stays_with' => 'living on my own',
                    'working_student' => 'yes full time',
                    'talents_skills' => json_encode(['drafting', 'calculations', 'project management']),
                    'leisure_activities' => json_encode(['basketball', 'engineering projects']),
                    'serious_medical_condition' => 'None',
                    'physical_disability' => 'None',
                    'sex_identity' => 'male/man',
                    'romantic_attraction' => 'opposite sex',
                ],
            ]
        );

        $this->seedStudent(
            userData: [
                'first_name' => 'Ana',
                'middle_name' => 'Marie',
                'last_name' => 'Lim',
                'birthdate' => '2003-05-20',
                'age' => 21,
                'sex' => 'female',
                'birthplace' => 'Iligan City',
                'religion' => 'Catholic',
                'civil_status' => 'single',
                'number_of_children' => 0,
                'citizenship' => 'Filipino',
                'address' => 'Iligan City',
                'phone_number' => '09120000003',
                'email' => 'coet@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
            ],
            studentData: [
                'student_id' => '2023-1003',
                'year_level' => '2nd Year',
                'course' => 'Bachelor of Science in Industrial Automation Engineering Technology',
                'college_id' => 3,
                'msu_sase_score' => 84.3,
                'academic_year' => '2024-2025',
                'student_status' => 'shiftee',
            ],
            relations: [
                'personal' => [
                    'nickname' => 'Annie',
                    'home_address' => 'Tibanga, Iligan City',
                    'stays_with' => 'friends',
                    'working_student' => 'no and have no plan to work',
                    'talents_skills' => json_encode(['technical drawing', 'electronics', 'problem solving']),
                    'leisure_activities' => json_encode(['robotics', 'gaming']),
                    'serious_medical_condition' => 'None',
                    'physical_disability' => 'None',
                    'sex_identity' => 'female/woman',
                    'romantic_attraction' => 'prefer not to answer',
                ],
            ]
        );

        $this->seedStudent(
            userData: [
                'first_name' => 'Sofia',
                'middle_name' => 'Isabel',
                'last_name' => 'Tan',
                'birthdate' => '2004-07-18',
                'age' => 20,
                'sex' => 'female',
                'birthplace' => 'Iligan City',
                'religion' => 'Christian',
                'civil_status' => 'single',
                'number_of_children' => 0,
                'citizenship' => 'Filipino',
                'address' => 'Iligan City',
                'phone_number' => '09120000004',
                'email' => 'ceba@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
            ],
            studentData: [
                'student_id' => '2023-1004',
                'year_level' => '1st Year',
                'course' => 'Bachelor of Science in Business Administration',
                'college_id' => 4,
                'msu_sase_score' => 86.1,
                'academic_year' => '2024-2025',
                'student_status' => 'transferee',
            ],
            relations: [
                'personal' => [
                    'nickname' => 'Sophie',
                    'home_address' => 'Palao, Iligan City',
                    'stays_with' => 'parents/guardian',
                    'working_student' => 'no but planning to work',
                    'talents_skills' => json_encode(['leadership', 'negotiation', 'public speaking']),
                    'leisure_activities' => json_encode(['business case studies', 'networking events']),
                    'serious_medical_condition' => 'None',
                    'physical_disability' => 'None',
                    'sex_identity' => 'female/woman',
                    'romantic_attraction' => 'opposite sex',
                ],
            ]
        );

        $this->seedStudent(
            userData: [
                'first_name' => 'Miguel',
                'middle_name' => 'Antonio',
                'last_name' => 'Cruz',
                'birthdate' => '2003-08-09',
                'age' => 21,
                'sex' => 'male',
                'birthplace' => 'Iligan City',
                'religion' => 'Catholic',
                'civil_status' => 'single',
                'number_of_children' => 0,
                'citizenship' => 'Filipino',
                'address' => 'Iligan City',
                'phone_number' => '09120000005',
                'email' => 'ced@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
            ],
            studentData: [
                'student_id' => '2023-1005',
                'year_level' => '4th Year',
                'course' => 'Bachelor of Secondary Education',
                'college_id' => 5,
                'msu_sase_score' => 89.7,
                'academic_year' => '2024-2025',
                'student_status' => 'new',
            ],
            relations: [
                'personal' => [
                    'nickname' => 'Mike',
                    'home_address' => 'Tominobo, Iligan City',
                    'stays_with' => 'relatives',
                    'working_student' => 'yes part time',
                    'talents_skills' => json_encode(['teaching', 'communication', 'lesson planning']),
                    'leisure_activities' => json_encode(['tutoring', 'reading educational materials']),
                    'serious_medical_condition' => 'None',
                    'physical_disability' => 'None',
                    'sex_identity' => 'male/man',
                    'romantic_attraction' => 'opposite sex',
                ],
            ]
        );

        $this->seedStudent(
            userData: [
                'first_name' => 'Elena',
                'middle_name' => 'Gabrielle',
                'last_name' => 'Rodriguez',
                'birthdate' => '2004-02-25',
                'age' => 21,
                'sex' => 'female',
                'birthplace' => 'Iligan City',
                'religion' => 'Christian',
                'civil_status' => 'single',
                'number_of_children' => 0,
                'citizenship' => 'Filipino',
                'address' => 'Iligan City',
                'phone_number' => '09120000006',
                'email' => 'csm@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
            ],
            studentData: [
                'student_id' => '2023-1006',
                'year_level' => '2nd Year',
                'course' => 'Bachelor of Science in Biology',
                'college_id' => 6,
                'msu_sase_score' => 91.2,
                'academic_year' => '2024-2025',
                'student_status' => 'new',
            ],
            relations: [
                'personal' => [
                    'nickname' => 'Lena',
                    'home_address' => 'Tibanga, Iligan City',
                    'stays_with' => 'parents/guardian',
                    'working_student' => 'no and have no plan to work',
                    'talents_skills' => json_encode(['laboratory work', 'research', 'scientific writing']),
                    'leisure_activities' => json_encode(['nature walks', 'scientific experiments']),
                    'serious_medical_condition' => 'None',
                    'physical_disability' => 'None',
                    'sex_identity' => 'female/woman',
                    'romantic_attraction' => 'prefer not to answer',
                ],
            ]
        );

        $this->seedStudent(
            userData: [
                'first_name' => 'James',
                'middle_name' => 'Robert',
                'last_name' => 'Wilson',
                'birthdate' => '2003-11-30',
                'age' => 21,
                'sex' => 'male',
                'birthplace' => 'Iligan City',
                'religion' => 'Protestant',
                'civil_status' => 'single',
                'number_of_children' => 0,
                'citizenship' => 'Filipino',
                'address' => 'Iligan City',
                'phone_number' => '09120000007',
                'email' => 'student2@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
            ],
            studentData: [
                'student_id' => '2023-1007',
                'year_level' => '1st Year',
                'course' => 'Bachelor of Science in Information Technology',
                'college_id' => 7,
                'msu_sase_score' => 83.4,
                'academic_year' => '2024-2025',
                'student_status' => 'new',
            ],
            relations: [
                'personal' => [
                    'nickname' => 'Jim',
                    'home_address' => 'Palao, Iligan City',
                    'stays_with' => 'board/roommates',
                    'working_student' => 'yes part time',
                    'talents_skills' => json_encode(['networking', 'system administration']),
                    'leisure_activities' => json_encode(['gaming', 'tech tutorials']),
                    'serious_medical_condition' => 'None',
                    'physical_disability' => 'None',
                    'sex_identity' => 'male/man',
                    'romantic_attraction' => 'opposite sex',
                ],
            ]
        );

        $this->seedStudent(
            userData: [
                'first_name' => 'Andrea',
                'middle_name' => 'Nicole',
                'last_name' => 'Martinez',
                'birthdate' => '2004-04-12',
                'age' => 20,
                'sex' => 'female',
                'birthplace' => 'Iligan City',
                'religion' => 'Catholic',
                'civil_status' => 'single',
                'number_of_children' => 0,
                'citizenship' => 'Filipino',
                'address' => 'Iligan City',
                'phone_number' => '09120000008',
                'email' => 'student3@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
            ],
            studentData: [
                'student_id' => '2023-1008',
                'year_level' => '3rd Year',
                'course' => 'Bachelor of Science in Psychology',
                'college_id' => 1,
                'msu_sase_score' => 87.1,
                'academic_year' => '2024-2025',
                'student_status' => 'new',
            ],
            relations: [
                'personal' => [
                    'nickname' => 'Andie',
                    'home_address' => 'Tibanga, Iligan City',
                    'stays_with' => 'parents/guardian',
                    'working_student' => 'no and have no plan to work',
                    'talents_skills' => json_encode(['counseling', 'active listening', 'empathy']),
                    'leisure_activities' => json_encode(['reading psychology books', 'meditation']),
                    'serious_medical_condition' => 'None',
                    'physical_disability' => 'None',
                    'sex_identity' => 'female/woman',
                    'romantic_attraction' => 'both men and women',
                ],
            ]
        );

        $this->seedStudent(
            userData: [
                'first_name' => 'Rose Andrea',
                'middle_name' => 'D',
                'last_name' => 'Kisol',
                'birthdate' => '2003-05-14',
                'age' => 22,
                'sex' => 'female',
                'birthplace' => 'Iligan City',
                'religion' => 'Roman Catholic',
                'civil_status' => 'single',
                'number_of_children' => 0,
                'citizenship' => 'Filipino',
                'address' => 'Tibanga, Iligan City',
                'phone_number' => '09171234567',
                'email' => 'roseandrea.kisol@g.msuiit.edu.ph',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
            ],
            studentData: [
                'student_id' => '2022-3041',
                'year_level' => '3rd Year',
                'course' => 'Bachelor of Science in Computer Science',
                'college_id' => 7,
                'msu_sase_score' => 90.3,
                'academic_year' => '2024-2025',
                'student_status' => 'new',
            ],
            relations: [
                'personal' => [
                    'nickname' => 'Rose',
                    'home_address' => 'Tibanga, Iligan City',
                    'stays_with' => 'parents/guardian',
                    'working_student' => 'no and have no plan to work',
                    'talents_skills' => json_encode(['programming', 'UI/UX design', 'problem solving']),
                    'leisure_activities' => json_encode(['reading', 'coding', 'drawing']),
                    'serious_medical_condition' => 'None',
                    'physical_disability' => 'None',
                    'sex_identity' => 'female/woman',
                    'romantic_attraction' => 'opposite sex',
                ],
                'family' => [
                    'father_name' => 'Roberto Kisol',
                    'father_deceased' => false,
                    'father_occupation' => 'Government Employee',
                    'father_phone_number' => '09181234567',
                    'mother_name' => 'Divina Kisol',
                    'mother_deceased' => false,
                    'mother_occupation' => 'Teacher',
                    'mother_phone_number' => '09191234567',
                    'parents_marital_status' => 'married',
                    'family_monthly_income' => '20001 above',
                    'guardian_name' => null,
                    'guardian_occupation' => null,
                    'guardian_phone_number' => null,
                    'guardian_relationship' => null,
                    'ordinal_position' => 'youngest',
                    'number_of_siblings' => 2,
                    'home_environment_description' => 'Warm and supportive family environment',
                ],
                'academic' => [
                    'shs_gpa' => 94.0,
                    'is_scholar' => true,
                    'scholarship_type' => 'Academic Scholar',
                    'school_last_attended' => 'Iligan City National High School',
                    'school_address' => 'Iligan City',
                    'shs_track' => 'academic',
                    'shs_strand' => 'STEM',
                    'awards_honors' => json_encode(['With Honors', 'Best in ICT']),
                    'student_organizations' => json_encode(['Computer Science Society', 'Women in Tech']),
                    'co_curricular_activities' => json_encode(['Hackathon', 'Programming Contest']),
                    'career_option_1' => 'Software Engineer',
                    'career_option_2' => 'UI/UX Designer',
                    'career_option_3' => 'Full Stack Developer',
                    'course_choice_by' => 'own choice',
                    'course_choice_reason' => 'Passion for technology and creating impactful software',
                    'msu_choice_reasons' => json_encode(['quality education', 'affordable tuition fees', 'close to home']),
                    'future_career_plans' => 'Work as a software engineer in a tech company and eventually build my own startup',
                ],
                'learning_resources' => [
                    'internet_access' => 'full internet access',
                    'technology_gadgets' => json_encode(['laptop/notebook', 'mobile phone smartphone', 'tablet']),
                    'internet_connectivity' => json_encode(['home internet', 'mobile data']),
                    'distance_learning_readiness' => 'fully ready',
                    'learning_space_description' => 'Dedicated study room with good lighting and fast internet',
                ],
                'psychosocial' => [
                    'personality_characteristics' => json_encode(['creative', 'determined', 'empathetic']),
                    'coping_mechanisms' => json_encode(['drawing', 'listening to music', 'talking to family']),
                    'mental_health_perception' => 'Generally positive, manages stress well',
                    'had_counseling_before' => false,
                    'sought_psychologist_help' => false,
                    'problem_sharing_targets' => json_encode(['mother', 'close friends']),
                    'needs_immediate_counseling' => false,
                    'future_counseling_concerns' => 'Career planning and academic workload management',
                ],
                'needs_assessment' => [
                    'improvement_needs' => json_encode(['Time-management skills', 'Study habits']),
                    'financial_assistance_needs' => json_encode(['Grants/scholarships']),
                    'personal_social_needs' => json_encode(['Stress management', 'Self-confidence']),
                    'stress_responses' => json_encode(['Listened to music', 'Talked to someone I trust']),
                    'easy_discussion_target' => 'parents',
                    'counseling_perceptions' => json_encode([
                        'I know that help is available at the Guidance and Counseling Center of MSU-IIT.' => 'always',
                        'I am shy to ask assistance/seek counseling from my guidance counselor.' => 'rarely',
                    ]),
                ],
            ]
        );
    }

    private function seedStudent(array $userData, array $studentData, array $relations = []): void
    {
        $user = $this->upsertUser($userData);
        $student = $this->upsertStudent($user->id, $studentData);

        $modelMap = [
            'personal' => StudentPersonalData::class,
            'family' => StudentFamilyData::class,
            'academic' => StudentAcademicData::class,
            'learning_resources' => StudentLearningResources::class,
            'psychosocial' => StudentPsychosocialData::class,
            'needs_assessment' => StudentNeedsAssessment::class,
        ];

        foreach ($relations as $key => $data) {
            if (! isset($modelMap[$key])) {
                continue;
            }

            $modelMap[$key]::updateOrCreate(
                ['student_id' => $student->id],
                $data
            );
        }
    }

    private function upsertUser(array $userData): User
    {
        $email = $userData['email'];
        unset($userData['email']);

        return User::updateOrCreate(
            ['email' => $email],
            $userData
        );
    }

    private function upsertStudent(int $userId, array $studentData): Student
    {
        $studentNumber = $studentData['student_id'];
        unset($studentData['student_id']);
        $studentData['user_id'] = $userId;

        return Student::updateOrCreate(
            ['student_id' => $studentNumber],
            $studentData
        );
    }
}
