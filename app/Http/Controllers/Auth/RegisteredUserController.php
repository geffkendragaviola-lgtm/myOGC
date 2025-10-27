<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\Admin;
use App\Models\College;
use App\Models\StudentPersonalData;
use App\Models\StudentFamilyData;
use App\Models\StudentAcademicData;
use App\Models\StudentLearningResources;
use App\Models\StudentPsychosocialData;
use App\Models\StudentNeedsAssessment;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $colleges = College::all();
        return view('auth.register', compact('colleges'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'birthdate' => ['nullable', 'date'],
            'sex' => ['nullable', 'string', 'in:male,female,other'],
            'birthplace' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:100'],
            'civil_status' => ['nullable', 'string', 'in:single,married,not legally married,divorced,widowed,separated,others'],
            'number_of_children' => ['nullable', 'integer', 'min:0'],
            'citizenship' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:100',
                'unique:'.User::class,
                'regex:/^[a-zA-Z0-9._%+-]+@g\.msuiit\.edu\.ph$/i'
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:student,counselor,admin'],
        ];

        // Add role-specific rules conditionally
        if ($request->role === 'student') {
            // School Data
            $rules['student_id'] = ['required', 'string', 'max:50', 'unique:students'];
            $rules['year_level'] = ['required', 'string', 'max:50'];
            $rules['course'] = ['required', 'string', 'max:100'];
            $rules['college_id'] = ['required', 'exists:colleges,id'];
            $rules['msu_sase_score'] = ['nullable', 'numeric', 'min:0', 'max:100'];
            $rules['academic_year'] = ['nullable', 'string', 'max:20'];
            $rules['student_status'] = ['required', 'string', 'in:new,transferee,returnee,shiftee'];

            // Personal Data
            $rules['nickname'] = ['nullable', 'string', 'max:100'];
            $rules['home_address'] = ['nullable', 'string'];
            $rules['stays_with'] = ['nullable', 'string', 'in:parents/guardian,board/roommates,relatives,friends,employer,living on my own'];
            $rules['working_student'] = ['nullable', 'string', 'in:yes full time,yes part time,no but planning to work,no and have no plan to work'];
            $rules['talents_skills'] = ['nullable', 'string'];
            $rules['leisure_activities'] = ['nullable', 'string'];
            $rules['serious_medical_condition'] = ['nullable', 'string', 'max:255'];
            $rules['physical_disability'] = ['nullable', 'string', 'max:255'];
            $rules['gender_identity'] = ['nullable', 'string', 'in:male/man,female/woman,transgender male/man,transgender female/woman,gender variant/nonconforming,not listed,prefer not to say'];
            $rules['romantic_attraction'] = ['nullable', 'string', 'in:my same gender,opposite gender,both men and women,all genders,neither gender,prefer not to answer'];

            // Family Data
            $rules['father_name'] = ['nullable', 'string', 'max:100'];
            $rules['father_occupation'] = ['nullable', 'string', 'max:100'];
            $rules['father_phone_number'] = ['nullable', 'string', 'max:20'];
            $rules['mother_name'] = ['nullable', 'string', 'max:100'];
            $rules['mother_occupation'] = ['nullable', 'string', 'max:100'];
            $rules['mother_phone_number'] = ['nullable', 'string', 'max:20'];
            $rules['parents_marital_status'] = ['nullable', 'string', 'in:married,not legally married,separated,both parents remarried,one parent remarried'];
            $rules['family_monthly_income'] = ['nullable', 'string', 'in:below 3k,3001-5000,5001-8000,8001-10000,10001-15000,15001-20000,20001 above'];
            $rules['guardian_name'] = ['nullable', 'string', 'max:100'];
            $rules['guardian_occupation'] = ['nullable', 'string', 'max:100'];
            $rules['guardian_phone_number'] = ['nullable', 'string', 'max:20'];
            $rules['guardian_relationship'] = ['nullable', 'string', 'max:50'];
            $rules['ordinal_position'] = ['nullable', 'string', 'in:only child,eldest,middle,youngest'];
            $rules['number_of_siblings'] = ['nullable', 'integer', 'min:0'];
            $rules['home_environment_description'] = ['nullable', 'string'];

            // Academic Data
            $rules['shs_gpa'] = ['nullable', 'numeric', 'min:0', 'max:100'];
            $rules['is_scholar'] = ['nullable', 'boolean'];
            $rules['scholarship_type'] = ['nullable', 'string', 'max:100'];
            $rules['school_last_attended'] = ['nullable', 'string', 'max:255'];
            $rules['school_address'] = ['nullable', 'string', 'max:255'];
            $rules['shs_track'] = ['nullable', 'string', 'in:academic,arts/design,tech-voc,sports'];
            $rules['shs_strand'] = ['nullable', 'string', 'in:GA,STEM,HUMMS,ABM'];
            $rules['career_option_1'] = ['nullable', 'string', 'max:100'];
            $rules['career_option_2'] = ['nullable', 'string', 'max:100'];
            $rules['career_option_3'] = ['nullable', 'string', 'max:100'];
            $rules['course_choice_by'] = ['nullable', 'string', 'in:own choice,parents choice,relative choice,sibling choice,according to MSU-SASE score/slot,others'];
            $rules['future_career_plans'] = ['nullable', 'string'];

            // Learning Resources
            $rules['internet_access'] = ['nullable', 'string', 'in:no internet access,limited internet access,full internet access'];
            $rules['distance_learning_readiness'] = ['nullable', 'string', 'in:fully ready,ready,a little ready,not ready'];
            $rules['learning_space_description'] = ['nullable', 'string'];

            // Psychosocial Data
            $rules['personality_characteristics'] = ['nullable', 'string'];
            $rules['coping_mechanisms'] = ['nullable', 'string'];
            $rules['mental_health_perception'] = ['nullable', 'string'];
            $rules['had_counseling_before'] = ['nullable', 'boolean'];
            $rules['sought_psychologist_help'] = ['nullable', 'boolean'];
            $rules['needs_immediate_counseling'] = ['nullable', 'boolean'];
            $rules['future_counseling_concerns'] = ['nullable', 'string'];

            // Needs Assessment
            $rules['easy_discussion_target'] = ['nullable', 'string', 'in:guidance counselor,parents,teachers,brothers/sisters,friends/relatives,nobody,others'];

        } elseif ($request->role === 'counselor') {
            $rules['position'] = ['required', 'string', 'max:100'];
            $rules['credentials'] = ['required', 'string', 'max:255'];
            $rules['counselor_college_id'] = ['required', 'exists:colleges,id'];
            $rules['is_head'] = ['nullable', 'boolean'];
        } elseif ($request->role === 'admin') {
            $rules['admin_credentials'] = ['required', 'string', 'max:255'];
        }

        // Validate with custom error message
        $request->validate($rules, [
            'email.regex' => 'You must use your MSU-IIT email (@g.msuiit.edu.ph).',
        ]);

        // Use transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Calculate age from birthdate
            $age = null;
            if ($request->birthdate) {
                $age = Carbon::parse($request->birthdate)->age;
            }

            // Step 1: Create User
            $user = User::create([
                'first_name' => strip_tags($request->first_name),
                'middle_name' => $request->middle_name ? strip_tags($request->middle_name) : null,
                'last_name' => strip_tags($request->last_name),
                'birthdate' => $request->birthdate,
                'age' => $age,
                'sex' => $request->sex,
                'birthplace' => $request->birthplace ? strip_tags($request->birthplace) : null,
                'religion' => $request->religion ? strip_tags($request->religion) : null,
                'civil_status' => $request->civil_status,
                'number_of_children' => $request->number_of_children ?? 0,
                'citizenship' => $request->citizenship ? strip_tags($request->citizenship) : null,
                'address' => $request->address ? strip_tags($request->address) : null,
                'phone_number' => $request->phone_number,
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Step 2: Create role-specific record
            switch ($request->role) {
                case 'student':
                    // Create main student record
                    $student = Student::create([
                        'user_id' => $user->id,
                        'student_id' => $request->student_id,
                        'year_level' => $request->year_level,
                        'course' => strip_tags($request->course),
                        'college_id' => $request->college_id,
                        'msu_sase_score' => $request->msu_sase_score,
                        'academic_year' => $request->academic_year,
                        'student_status' => $request->student_status,
                    ]);

                    // Create Personal Data
                    StudentPersonalData::create([
                        'student_id' => $student->id,
                        'nickname' => $request->nickname ? strip_tags($request->nickname) : null,
                        'home_address' => $request->home_address ? strip_tags($request->home_address) : null,
                        'stays_with' => $request->stays_with,
                        'working_student' => $request->working_student,
'talents_skills' => $request->talents_skills ? json_encode(array_map('trim', explode(',', $request->talents_skills))) : null,
'leisure_activities' => $request->leisure_activities ? json_encode(array_map('trim', explode(',', $request->leisure_activities))) : null,
'serious_medical_condition' => $request->serious_medical_condition ? strip_tags($request->serious_medical_condition) : null,
                        'physical_disability' => $request->physical_disability ? strip_tags($request->physical_disability) : null,
                        'gender_identity' => $request->gender_identity,
                        'romantic_attraction' => $request->romantic_attraction,
                    ]);

                    // Create Family Data
                    StudentFamilyData::create([
                        'student_id' => $student->id,
                        'father_name' => $request->father_name ? strip_tags($request->father_name) : null,
                        'father_deceased' => $request->has('father_deceased'),
                        'father_occupation' => $request->father_occupation ? strip_tags($request->father_occupation) : null,
                        'father_phone_number' => $request->father_phone_number,
                        'mother_name' => $request->mother_name ? strip_tags($request->mother_name) : null,
                        'mother_deceased' => $request->has('mother_deceased'),
                        'mother_occupation' => $request->mother_occupation ? strip_tags($request->mother_occupation) : null,
                        'mother_phone_number' => $request->mother_phone_number,
                        'parents_marital_status' => $request->parents_marital_status,
                        'family_monthly_income' => $request->family_monthly_income,
                        'guardian_name' => $request->guardian_name ? strip_tags($request->guardian_name) : null,
                        'guardian_occupation' => $request->guardian_occupation ? strip_tags($request->guardian_occupation) : null,
                        'guardian_phone_number' => $request->guardian_phone_number,
                        'guardian_relationship' => $request->guardian_relationship ? strip_tags($request->guardian_relationship) : null,
                        'ordinal_position' => $request->ordinal_position,
                        'number_of_siblings' => $request->number_of_siblings ?? 0,
                        'home_environment_description' => $request->home_environment_description ? strip_tags($request->home_environment_description) : null,
                    ]);

                    // Create Academic Data
                    StudentAcademicData::create([
                        'student_id' => $student->id,
                        'shs_gpa' => $request->shs_gpa,
                        'is_scholar' => $request->is_scholar ?? false,
                        'scholarship_type' => $request->scholarship_type ? strip_tags($request->scholarship_type) : null,
                        'school_last_attended' => $request->school_last_attended ? strip_tags($request->school_last_attended) : null,
                        'school_address' => $request->school_address ? strip_tags($request->school_address) : null,
                        'shs_track' => $request->shs_track,
                        'shs_strand' => $request->shs_strand,
                       'awards_honors' => $request->awards_honors ?
    json_encode(array_map('trim', explode(',', $request->awards_honors))) : null,
'student_organizations' => $request->student_organizations ?
    json_encode(array_map('trim', explode(',', $request->student_organizations))) : null,
'co_curricular_activities' => $request->co_curricular_activities ?
    json_encode(array_map('trim', explode(',', $request->co_curricular_activities))) : null,
    'career_option_1' => $request->career_option_1 ? strip_tags($request->career_option_1) : null,
                        'career_option_2' => $request->career_option_2 ? strip_tags($request->career_option_2) : null,
                        'career_option_3' => $request->career_option_3 ? strip_tags($request->career_option_3) : null,
                        'course_choice_by' => $request->course_choice_by,
                        'course_choice_reason' => $request->course_choice_reason ? strip_tags($request->course_choice_reason) : null,
                        'msu_choice_reasons' => $request->msu_choice_reasons
    ? json_encode($request->msu_choice_reasons)
    : null,

                        'future_career_plans' => $request->future_career_plans ? strip_tags($request->future_career_plans) : null,
                    ]);

                    // Create Learning Resources
                    StudentLearningResources::create([
                        'student_id' => $student->id,
                        'internet_access' => $request->internet_access,
                      'technology_gadgets' => $request->technology_gadgets
    ? json_encode($request->technology_gadgets)
    : null,

'internet_connectivity' => $request->internet_connectivity
    ? json_encode($request->internet_connectivity)
    : null,
'distance_learning_readiness' => $request->distance_learning_readiness,
                        'learning_space_description' => $request->learning_space_description ? strip_tags($request->learning_space_description) : null,
                    ]);

                    // Create Psychosocial Data
                    StudentPsychosocialData::create([
                        'student_id' => $student->id,
                     'personality_characteristics' => $request->personality_characteristics ?
    json_encode(array_map('trim', explode(',', $request->personality_characteristics))) : null,
'coping_mechanisms' => $request->coping_mechanisms ?
    json_encode(array_map('trim', explode(',', $request->coping_mechanisms))) : null,
                            'mental_health_perception' => $request->mental_health_perception ? strip_tags($request->mental_health_perception) : null,
                        'had_counseling_before' => $request->had_counseling_before ?? false,
                        'sought_psychologist_help' => $request->sought_psychologist_help ?? false,
                       'problem_sharing_targets' => $request->problem_sharing_targets ? json_encode($request->problem_sharing_targets) : null,
                       'needs_immediate_counseling' => $request->needs_immediate_counseling ?? false,
                        'future_counseling_concerns' => $request->future_counseling_concerns ? strip_tags($request->future_counseling_concerns) : null,
                    ]);

                    // Create Needs Assessment
                    StudentNeedsAssessment::create([
                        'student_id' => $student->id,
                        'improvement_needs' => $request->improvement_needs ? json_encode($request->improvement_needs) : null,
                        'financial_assistance_needs' => $request->financial_assistance_needs ? json_encode($request->financial_assistance_needs) : null,
                        'personal_social_needs' => $request->personal_social_needs ? json_encode($request->personal_social_needs) : null,
                        'stress_responses' => $request->stress_responses ? json_encode($request->stress_responses) : null,
                        'easy_discussion_target' => $request->easy_discussion_target,
                        'counseling_perceptions' => $request->counseling_perceptions ? json_encode($request->counseling_perceptions) : null,
                    ]);
                    break;

                case 'counselor':
                    Counselor::create([
                        'user_id' => $user->id,
                        'position' => strip_tags($request->position),
                        'credentials' => strip_tags($request->credentials),
                        'college_id' => $request->counselor_college_id,
                        'is_head' => $request->is_head ?? false,
                    ]);
                    break;

                case 'admin':
                    Admin::create([
                        'user_id' => $user->id,
                        'credentials' => strip_tags($request->admin_credentials),
                    ]);
                    break;
            }

            DB::commit();

            event(new Registered($user));
            Auth::login($user);

            // Redirect based on role
            return redirect()->intended(
                $user->role === 'admin' ? route('admin.dashboard') :
                ($user->role === 'counselor' ? route('counselor.dashboard') : route('student.dashboard'))
            );

        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error with more details
            Log::error('Registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}
