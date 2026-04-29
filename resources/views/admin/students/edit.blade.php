@extends('layouts.admin')

@section('title', 'Edit Student - Admin Panel')

@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c;
        --maroon-800: #5c1a1a;
        --maroon-700: #7a2a2a;
        --gold-500: #c9a227;
        --gold-400: #d4af37;
        --bg-warm: #faf8f5;
        --border-soft: #e5e0db;
        --text-primary: #2c2420;
        --text-secondary: #6b5e57;
        --text-muted: #8b7e76;
    }

    .edit-student-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .edit-student-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .edit-student-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .edit-student-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .section-card, .summary-card, .glass-alert {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .section-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .section-card::before, .glass-alert::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .section-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-icon {
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.8);
        padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.16em; color: var(--maroon-700);
    }
    .hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: var(--gold-400); }

    .summary-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid rgba(92,26,26,0.15);
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%); color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%);
        pointer-events: none;
    }
    .summary-avatar {
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1);
        color: #fef9e7; flex-shrink: 0; font-weight: 700;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.15rem; line-height: 1.25; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

    .section-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .section-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; background: rgba(250,248,245,0.5); }
    .section-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .section-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .section-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; }
    .field-input, .field-select, .field-textarea {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .field-textarea { padding: 0.65rem 0.75rem; resize: vertical; }
    .field-input:focus, .field-select:focus, .field-textarea:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

    .checkbox-card {
        display: flex; align-items: flex-start; padding: 0.7rem 0.85rem; border-radius: 0.65rem;
        background: rgba(250,248,245,0.7); border: 1px solid var(--border-soft);
        transition: all 0.2s ease;
    }
    .checkbox-card:hover { border-color: rgba(212,175,55,0.4); background: rgba(254,249,231,0.5); }
    .checkbox-card label { cursor: pointer; margin-top: 0.1rem; }

    .error-text { font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem; }
    .success-alert, .error-alert { border-radius: 0.6rem; padding: 0.65rem 0.85rem; border-width: 1px; }
    .success-alert { background: rgba(236,253,245,0.8); border-color: #10b981/30; color: #059669; }
    .error-alert { background: rgba(254,242,242,0.8); border-color: #b91c1c/30; color: #b91c1c; }

    .primary-btn, .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        padding: 0.55rem 0.85rem;
    }
    .primary-btn { color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%); box-shadow: 0 4px 10px rgba(92,26,26,0.15); }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn { background: #ffffff; color: var(--text-secondary); border: 1px solid var(--border-soft); box-shadow: 0 2px 6px rgba(44,36,32,0.03); }
    .secondary-btn:hover { background: #f5f0eb; }
    .tiny-note { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.3rem; }

    @media (max-width: 639px) {
        .section-header { padding: 0.75rem 1rem; }
        .field-input, .field-select { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; padding: 0.6rem 1rem; }
        .space-y-6 > :not([hidden]) ~ :not([hidden]) { margin-top: 0.75rem !important; }
        .checkbox-card { margin-top: 0 !important; }
        .mt-0.md\:mt-7 { margin-top: 0 !important; }
    }
</style>

<div class="min-h-screen edit-student-shell">
    <div class="edit-student-glow one"></div>
    <div class="edit-student-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
                        <div class="hero-icon flex-shrink-0">
                            <i class="fas fa-user-pen text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('admin.students') }}" class="inline-flex items-center text-[#7a2a2a] hover:text-[#5c1a1a] mb-3 sm:mb-4 font-medium text-xs sm:text-sm">
                                <i class="fas fa-arrow-left mr-1.5"></i> Back to Students
                            </a>
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Student Profile Editor
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Edit Student</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Update student profile details, academic background, family information, and assessment records.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card relative p-4 sm:p-5">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="summary-avatar flex-shrink-0">
                            {{ strtoupper(substr($student->user->first_name, 0, 1)) }}{{ strtoupper(substr($student->user->last_name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="summary-label">Student Summary</div>
                            <div class="summary-value truncate">{{ $student->user->first_name }} {{ $student->user->last_name }}</div>
                            <div class="summary-subtext">{{ $student->student_id }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="glass-alert error-alert mb-4 sm:mb-6">
                <div class="flex items-center text-xs sm:text-sm">
                    <i class="fas fa-circle-exclamation mr-2"></i>
                    <div>
                        <div class="font-semibold">Please fix the errors below.</div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-4 sm:space-y-6">
            @csrf
            @method('patch')

            <!-- User Information -->
            <div class="section-card">
                <div class="section-topline"></div>
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-user text-xs sm:text-sm"></i></div>
                    <div>
                        <h2 class="section-title">User Information</h2>
                        <p class="section-subtitle hidden sm:block">Basic identity, contact details, and personal background.</p>
                    </div>
                </div>

                <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <div>
                        <label class="field-label">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->user->first_name) }}" class="field-input">
                        @error('first_name')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $student->user->middle_name) }}" class="field-input">
                        @error('middle_name')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $student->user->last_name) }}" class="field-input">
                        @error('last_name')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $student->user->email) }}" class="field-input">
                        @error('email')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $student->user->phone_number) }}" class="field-input">
                        @error('phone_number')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Birthdate</label>
                        <input type="date" name="birthdate" value="{{ old('birthdate', optional($student->user->birthdate)->format('Y-m-d')) }}" class="field-input">
                        @error('birthdate')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Sex</label>
                        <select name="sex" class="field-select">
                            <option value="">Select</option>
                            <option value="male" {{ old('sex', $student->user->sex) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex', $student->user->sex) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('sex', $student->user->sex) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('sex')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Civil Status</label>
                        <select name="civil_status" class="field-select">
                            <option value="">Select</option>
                            @foreach(['single','married','not legally married','divorced','widowed','separated','others'] as $cs)
                                <option value="{{ $cs }}" {{ old('civil_status', $student->user->civil_status) === $cs ? 'selected' : '' }}>{{ ucfirst($cs) }}</option>
                            @endforeach
                        </select>
                        @error('civil_status')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Number of Children</label>
                        <input type="number" min="0" name="number_of_children" value="{{ old('number_of_children', $student->user->number_of_children) }}" class="field-input">
                        @error('number_of_children')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Citizenship</label>
                        <input type="text" name="citizenship" value="{{ old('citizenship', $student->user->citizenship) }}" class="field-input">
                        @error('citizenship')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Birthplace</label>
                        <input type="text" name="birthplace" value="{{ old('birthplace', $student->user->birthplace) }}" class="field-input">
                        @error('birthplace')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Religion</label>
                        <input type="text" name="religion" value="{{ old('religion', $student->user->religion) }}" class="field-input">
                        @error('religion')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Address</label>
                        <textarea name="address" rows="3" class="field-textarea">{{ old('address', $student->user->address) }}</textarea>
                        @error('address')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Student Record -->
            <div class="section-card">
                <div class="section-topline"></div>
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-id-card text-xs sm:text-sm"></i></div>
                    <div>
                        <h2 class="section-title">Student Record</h2>
                        <p class="section-subtitle hidden sm:block">Core student identifiers, college assignment, and academic standing.</p>
                    </div>
                </div>

                <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <div>
                        <label class="field-label">Student ID</label>
                        <input type="text" name="student_id" value="{{ old('student_id', $student->student_id) }}" class="field-input">
                        @error('student_id')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">College</label>
                        <select name="college_id" class="field-select">
                            @foreach($colleges as $c)
                                <option value="{{ $c->id }}" {{ (string)old('college_id', $student->college_id) === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('college_id')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Course</label>
                        <input type="text" name="course" value="{{ old('course', $student->course) }}" class="field-input">
                        @error('course')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Year Level</label>
                        <input type="text" name="year_level" value="{{ old('year_level', $student->year_level) }}" class="field-input">
                        @error('year_level')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">MSU SASE Score</label>
                        <input type="number" step="0.01" name="msu_sase_score" value="{{ old('msu_sase_score', $student->msu_sase_score) }}" class="field-input">
                        @error('msu_sase_score')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Academic Year</label>
                        <input type="text" name="academic_year" value="{{ old('academic_year', $student->academic_year) }}" class="field-input">
                        @error('academic_year')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Student Status</label>
                        <select name="student_status" class="field-select">
                            @foreach(['new','transferee','returnee','shiftee'] as $st)
                                <option value="{{ $st }}" {{ old('student_status', $student->student_status) === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                        @error('student_status')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Personal Data -->
            <div class="section-card">
                <div class="section-topline"></div>
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-address-book text-xs sm:text-sm"></i></div>
                    <div>
                        <h2 class="section-title">Personal Data</h2>
                        <p class="section-subtitle hidden sm:block">Living setup, identity, interests, leisure, and health-related information.</p>
                    </div>
                </div>

                <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <div>
                        <label class="field-label">Nickname</label>
                        <input type="text" name="personal[nickname]" value="{{ old('personal.nickname', $student->personalData->nickname ?? '') }}" class="field-input">
                        @error('personal.nickname')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Stays With</label>
                        <select name="personal[stays_with]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['parents/guardian','board/roommates','relatives','friends','employer','living on my own'] as $sw)
                                <option value="{{ $sw }}" {{ old('personal.stays_with', $student->personalData->stays_with ?? '') === $sw ? 'selected' : '' }}>{{ $sw }}</option>
                            @endforeach
                        </select>
                        @error('personal.stays_with')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Working Student</label>
                        <select name="personal[working_student]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['yes full time','yes part time','no but planning to work','no and have no plan to work'] as $ws)
                                <option value="{{ $ws }}" {{ old('personal.working_student', $student->personalData->working_student ?? '') === $ws ? 'selected' : '' }}>{{ $ws }}</option>
                            @endforeach
                        </select>
                        @error('personal.working_student')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Sex Identity</label>
                        <select name="personal[sex_identity]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['male/man','female/woman','transsex male/man','transsex female/woman','sex variant/nonconforming','not listed','prefer not to say'] as $si)
                                <option value="{{ $si }}" {{ old('personal.sex_identity', $student->personalData->sex_identity ?? '') === $si ? 'selected' : '' }}>{{ $si }}</option>
                            @endforeach
                        </select>
                        @error('personal.sex_identity')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Romantic Attraction</label>
                        <select name="personal[romantic_attraction]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['my same sex','opposite sex','both men and women','all sexes','neither sex','prefer not to answer'] as $ra)
                                <option value="{{ $ra }}" {{ old('personal.romantic_attraction', $student->personalData->romantic_attraction ?? '') === $ra ? 'selected' : '' }}>{{ $ra }}</option>
                            @endforeach
                        </select>
                        @error('personal.romantic_attraction')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Home Address</label>
                        <textarea name="personal[home_address]" rows="2" class="field-textarea">{{ old('personal.home_address', $student->personalData->home_address ?? '') }}</textarea>
                        @error('personal.home_address')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Talents / Skills (comma or new line separated)</label>
                        <textarea name="personal[talents_skills]" rows="2" class="field-textarea">{{ old('personal.talents_skills', is_array($student->personalData->talents_skills ?? null) ? implode(", ", $student->personalData->talents_skills) : ($student->personalData->talents_skills ?? '')) }}</textarea>
                        @error('personal.talents_skills')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Leisure Activities (comma or new line separated)</label>
                        <textarea name="personal[leisure_activities]" rows="2" class="field-textarea">{{ old('personal.leisure_activities', is_array($student->personalData->leisure_activities ?? null) ? implode(", ", $student->personalData->leisure_activities) : ($student->personalData->leisure_activities ?? '')) }}</textarea>
                        @error('personal.leisure_activities')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Serious Medical Condition</label>
                        <input type="text" name="personal[serious_medical_condition]" value="{{ old('personal.serious_medical_condition', $student->personalData->serious_medical_condition ?? '') }}" class="field-input">
                        @error('personal.serious_medical_condition')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Physical Disability</label>
                        <input type="text" name="personal[physical_disability]" value="{{ old('personal.physical_disability', $student->personalData->physical_disability ?? '') }}" class="field-input">
                        @error('personal.physical_disability')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Family Data -->
            <div class="section-card">
                <div class="section-topline"></div>
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-people-roof text-xs sm:text-sm"></i></div>
                    <div>
                        <h2 class="section-title">Family Data</h2>
                        <p class="section-subtitle hidden sm:block">Parent information, household structure, and family environment.</p>
                    </div>
                </div>

                <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <div>
                        <label class="field-label">Father Name</label>
                        <input type="text" name="family[father_name]" value="{{ old('family.father_name', $student->familyData->father_name ?? '') }}" class="field-input">
                        @error('family.father_name')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div class="checkbox-card mt-0 md:mt-7">
                        <input type="hidden" name="family[father_deceased]" value="0">
                        <input type="checkbox" name="family[father_deceased]" value="1" class="mt-0.5 mr-2 h-4 w-4 rounded border-gray-300 text-[#7a2a2a] focus:ring-[#7a2a2a] flex-shrink-0" {{ old('family.father_deceased', $student->familyData->father_deceased ?? false) ? 'checked' : '' }}>
                        <label class="text-xs sm:text-sm font-medium text-[#4a3f3a]">Father Deceased</label>
                    </div>
                    <div>
                        <label class="field-label">Father Occupation</label>
                        <input type="text" name="family[father_occupation]" value="{{ old('family.father_occupation', $student->familyData->father_occupation ?? '') }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Father Phone</label>
                        <input type="text" name="family[father_phone_number]" value="{{ old('family.father_phone_number', $student->familyData->father_phone_number ?? '') }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Mother Name</label>
                        <input type="text" name="family[mother_name]" value="{{ old('family.mother_name', $student->familyData->mother_name ?? '') }}" class="field-input">
                    </div>
                    <div class="checkbox-card mt-0 md:mt-7">
                        <input type="hidden" name="family[mother_deceased]" value="0">
                        <input type="checkbox" name="family[mother_deceased]" value="1" class="mt-0.5 mr-2 h-4 w-4 rounded border-gray-300 text-[#7a2a2a] focus:ring-[#7a2a2a] flex-shrink-0" {{ old('family.mother_deceased', $student->familyData->mother_deceased ?? false) ? 'checked' : '' }}>
                        <label class="text-xs sm:text-sm font-medium text-[#4a3f3a]">Mother Deceased</label>
                    </div>
                    <div>
                        <label class="field-label">Mother Occupation</label>
                        <input type="text" name="family[mother_occupation]" value="{{ old('family.mother_occupation', $student->familyData->mother_occupation ?? '') }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Mother Phone</label>
                        <input type="text" name="family[mother_phone_number]" value="{{ old('family.mother_phone_number', $student->familyData->mother_phone_number ?? '') }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Parents Marital Status</label>
                        <select name="family[parents_marital_status]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['married','not legally married','separated','both parents remarried','one parent remarried'] as $pms)
                                <option value="{{ $pms }}" {{ old('family.parents_marital_status', $student->familyData->parents_marital_status ?? '') === $pms ? 'selected' : '' }}>{{ $pms }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Family Monthly Income</label>
                        <select name="family[family_monthly_income]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['below 3k','3001-5000','5001-8000','8001-10000','10001-15000','15001-20000','20001 above'] as $fmi)
                                <option value="{{ $fmi }}" {{ old('family.family_monthly_income', $student->familyData->family_monthly_income ?? '') === $fmi ? 'selected' : '' }}>{{ $fmi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Ordinal Position</label>
                        <select name="family[ordinal_position]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['only child','eldest','middle','youngest'] as $op)
                                <option value="{{ $op }}" {{ old('family.ordinal_position', $student->familyData->ordinal_position ?? '') === $op ? 'selected' : '' }}>{{ $op }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Number of Siblings</label>
                        <input type="number" min="0" name="family[number_of_siblings]" value="{{ old('family.number_of_siblings', $student->familyData->number_of_siblings ?? 0) }}" class="field-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Home Environment Description</label>
                        <textarea name="family[home_environment_description]" rows="3" class="field-textarea">{{ old('family.home_environment_description', $student->familyData->home_environment_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Academic Data -->
            <div class="section-card">
                <div class="section-topline"></div>
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-graduation-cap text-xs sm:text-sm"></i></div>
                    <div>
                        <h2 class="section-title">Academic Data</h2>
                        <p class="section-subtitle hidden sm:block">Scholarship details, SHS background, achievements, and career planning.</p>
                    </div>
                </div>

                <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <div>
                        <label class="field-label">SHS GPA</label>
                        <input type="number" step="0.01" name="academic[shs_gpa]" value="{{ old('academic.shs_gpa', $student->academicData->shs_gpa ?? '') }}" class="field-input">
                    </div>
                    <div class="checkbox-card mt-0 md:mt-7">
                        <input type="hidden" name="academic[is_scholar]" value="0">
                        <input type="checkbox" name="academic[is_scholar]" value="1" class="mt-0.5 mr-2 h-4 w-4 rounded border-gray-300 text-[#7a2a2a] focus:ring-[#7a2a2a] flex-shrink-0" {{ old('academic.is_scholar', $student->academicData->is_scholar ?? false) ? 'checked' : '' }}>
                        <label class="text-xs sm:text-sm font-medium text-[#4a3f3a]">Scholar</label>
                    </div>
                    <div>
                        <label class="field-label">Scholarship Type</label>
                        <input type="text" name="academic[scholarship_type]" value="{{ old('academic.scholarship_type', $student->academicData->scholarship_type ?? '') }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">SHS Track</label>
                        <select name="academic[shs_track]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['academic','arts/design','tech-voc','sports'] as $track)
                                <option value="{{ $track }}" {{ old('academic.shs_track', $student->academicData->shs_track ?? '') === $track ? 'selected' : '' }}>{{ $track }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">SHS Strand</label>
                        <select name="academic[shs_strand]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['GA','STEM','HUMMS','ABM'] as $strand)
                                <option value="{{ $strand }}" {{ old('academic.shs_strand', $student->academicData->shs_strand ?? '') === $strand ? 'selected' : '' }}>{{ $strand }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Awards / Honors (comma or new line separated)</label>
                        <textarea name="academic[awards_honors]" rows="2" class="field-textarea">{{ old('academic.awards_honors', is_array($student->academicData->awards_honors ?? null) ? implode(", ", $student->academicData->awards_honors) : ($student->academicData->awards_honors ?? '')) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Student Organizations (comma or new line separated)</label>
                        <textarea name="academic[student_organizations]" rows="2" class="field-textarea">{{ old('academic.student_organizations', is_array($student->academicData->student_organizations ?? null) ? implode(", ", $student->academicData->student_organizations) : ($student->academicData->student_organizations ?? '')) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Co-curricular Activities (comma or new line separated)</label>
                        <textarea name="academic[co_curricular_activities]" rows="2" class="field-textarea">{{ old('academic.co_curricular_activities', is_array($student->academicData->co_curricular_activities ?? null) ? implode(", ", $student->academicData->co_curricular_activities) : ($student->academicData->co_curricular_activities ?? '')) }}</textarea>
                    </div>
                    <div>
                        <label class="field-label">Career Option 1</label>
                        <input type="text" name="academic[career_option_1]" value="{{ old('academic.career_option_1', $student->academicData->career_option_1 ?? '') }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Career Option 2</label>
                        <input type="text" name="academic[career_option_2]" value="{{ old('academic.career_option_2', $student->academicData->career_option_2 ?? '') }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Career Option 3</label>
                        <input type="text" name="academic[career_option_3]" value="{{ old('academic.career_option_3', $student->academicData->career_option_3 ?? '') }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Course Choice By</label>
                        <select name="academic[course_choice_by]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['own choice','parents choice','relative choice','sibling choice','according to MSU-SASE score/slot','others'] as $ccb)
                                <option value="{{ $ccb }}" {{ old('academic.course_choice_by', $student->academicData->course_choice_by ?? '') === $ccb ? 'selected' : '' }}>{{ $ccb }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Course Choice Reason</label>
                        <textarea name="academic[course_choice_reason]" rows="2" class="field-textarea">{{ old('academic.course_choice_reason', $student->academicData->course_choice_reason ?? '') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">MSU Choice Reasons (comma or new line separated)</label>
                        <textarea name="academic[msu_choice_reasons]" rows="2" class="field-textarea">{{ old('academic.msu_choice_reasons', is_array($student->academicData->msu_choice_reasons ?? null) ? implode(", ", $student->academicData->msu_choice_reasons) : ($student->academicData->msu_choice_reasons ?? '')) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Future Career Plans</label>
                        <textarea name="academic[future_career_plans]" rows="2" class="field-textarea">{{ old('academic.future_career_plans', $student->academicData->future_career_plans ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Learning Resources -->
            <div class="section-card">
                <div class="section-topline"></div>
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-wifi text-xs sm:text-sm"></i></div>
                    <div>
                        <h2 class="section-title">Learning Resources</h2>
                        <p class="section-subtitle hidden sm:block">Technology access, connectivity, learning readiness, and study setup.</p>
                    </div>
                </div>

                <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <div>
                        <label class="field-label">Internet Access</label>
                        <select name="learning[internet_access]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['no internet access','limited internet access','full internet access'] as $ia)
                                <option value="{{ $ia }}" {{ old('learning.internet_access', $student->learningResources->internet_access ?? '') === $ia ? 'selected' : '' }}>{{ $ia }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Distance Learning Readiness</label>
                        <select name="learning[distance_learning_readiness]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['fully ready','ready','a little ready','not ready'] as $dlr)
                                <option value="{{ $dlr }}" {{ old('learning.distance_learning_readiness', $student->learningResources->distance_learning_readiness ?? '') === $dlr ? 'selected' : '' }}>{{ $dlr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Technology Gadgets (comma or new line separated)</label>
                        <textarea name="learning[technology_gadgets]" rows="2" class="field-textarea">{{ old('learning.technology_gadgets', is_array($student->learningResources->technology_gadgets ?? null) ? implode(", ", $student->learningResources->technology_gadgets) : ($student->learningResources->technology_gadgets ?? '')) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Internet Connectivity (comma or new line separated)</label>
                        <textarea name="learning[internet_connectivity]" rows="2" class="field-textarea">{{ old('learning.internet_connectivity', is_array($student->learningResources->internet_connectivity ?? null) ? implode(", ", $student->learningResources->internet_connectivity) : ($student->learningResources->internet_connectivity ?? '')) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Learning Space Description</label>
                        <textarea name="learning[learning_space_description]" rows="2" class="field-textarea">{{ old('learning.learning_space_description', $student->learningResources->learning_space_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Psychosocial Data -->
            <div class="section-card">
                <div class="section-topline"></div>
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-brain text-xs sm:text-sm"></i></div>
                    <div>
                        <h2 class="section-title">Psychosocial Data</h2>
                        <p class="section-subtitle hidden sm:block">Behavioral tendencies, coping, help-seeking history, and counseling concerns.</p>
                    </div>
                </div>

                <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <div class="md:col-span-2">
                        <label class="field-label">Personality Characteristics (comma or new line separated)</label>
                        <textarea name="psychosocial[personality_characteristics]" rows="2" class="field-textarea">{{ old('psychosocial.personality_characteristics', is_array($student->psychosocialData->personality_characteristics ?? null) ? implode(", ", $student->psychosocialData->personality_characteristics) : ($student->psychosocialData->personality_characteristics ?? '')) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Coping Mechanisms (comma or new line separated)</label>
                        <textarea name="psychosocial[coping_mechanisms]" rows="2" class="field-textarea">{{ old('psychosocial.coping_mechanisms', is_array($student->psychosocialData->coping_mechanisms ?? null) ? implode(", ", $student->psychosocialData->coping_mechanisms) : ($student->psychosocialData->coping_mechanisms ?? '')) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Problem Sharing Targets (comma or new line separated)</label>
                        <textarea name="psychosocial[problem_sharing_targets]" rows="2" class="field-textarea">{{ old('psychosocial.problem_sharing_targets', is_array($student->psychosocialData->problem_sharing_targets ?? null) ? implode(", ", $student->psychosocialData->problem_sharing_targets) : ($student->psychosocialData->problem_sharing_targets ?? '')) }}</textarea>
                    </div>
                    <div>
                        <label class="field-label">Mental Health Perception</label>
                        <input type="text" name="psychosocial[mental_health_perception]" value="{{ old('psychosocial.mental_health_perception', $student->psychosocialData->mental_health_perception ?? '') }}" class="field-input">
                    </div>
                    <div class="checkbox-card mt-0 md:mt-7">
                        <input type="hidden" name="psychosocial[had_counseling_before]" value="0">
                        <input type="checkbox" name="psychosocial[had_counseling_before]" value="1" class="mt-0.5 mr-2 h-4 w-4 rounded border-gray-300 text-[#7a2a2a] focus:ring-[#7a2a2a] flex-shrink-0" {{ old('psychosocial.had_counseling_before', $student->psychosocialData->had_counseling_before ?? false) ? 'checked' : '' }}>
                        <label class="text-xs sm:text-sm font-medium text-[#4a3f3a]">Had Counseling Before</label>
                    </div>
                    <div class="checkbox-card">
                        <input type="hidden" name="psychosocial[sought_psychologist_help]" value="0">
                        <input type="checkbox" name="psychosocial[sought_psychologist_help]" value="1" class="mt-0.5 mr-2 h-4 w-4 rounded border-gray-300 text-[#7a2a2a] focus:ring-[#7a2a2a] flex-shrink-0" {{ old('psychosocial.sought_psychologist_help', $student->psychosocialData->sought_psychologist_help ?? false) ? 'checked' : '' }}>
                        <label class="text-xs sm:text-sm font-medium text-[#4a3f3a]">Sought Psychologist Help</label>
                    </div>
                    <div class="checkbox-card">
                        <input type="hidden" name="psychosocial[needs_immediate_counseling]" value="0">
                        <input type="checkbox" name="psychosocial[needs_immediate_counseling]" value="1" class="mt-0.5 mr-2 h-4 w-4 rounded border-gray-300 text-[#7a2a2a] focus:ring-[#7a2a2a] flex-shrink-0" {{ old('psychosocial.needs_immediate_counseling', $student->psychosocialData->needs_immediate_counseling ?? false) ? 'checked' : '' }}>
                        <label class="text-xs sm:text-sm font-medium text-[#4a3f3a]">Needs Immediate Counseling</label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Future Counseling Concerns</label>
                        <textarea name="psychosocial[future_counseling_concerns]" rows="2" class="field-textarea">{{ old('psychosocial.future_counseling_concerns', $student->psychosocialData->future_counseling_concerns ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Needs Assessment -->
            <div class="section-card">
                <div class="section-topline"></div>
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-clipboard-check text-xs sm:text-sm"></i></div>
                    <div>
                        <h2 class="section-title">Needs Assessment</h2>
                        <p class="section-subtitle hidden sm:block">Support needs, stress responses, counseling perceptions, and discussion targets.</p>
                    </div>
                </div>

                <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <div class="md:col-span-2">
                        <label class="field-label">Improvement Needs (comma or new line separated)</label>
                        <textarea name="needs[improvement_needs]" rows="2" class="field-textarea">{{ old('needs.improvement_needs', is_array($student->needsAssessment->improvement_needs ?? null) ? implode(", ", $student->needsAssessment->improvement_needs) : ($student->needsAssessment->improvement_needs ?? '')) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Financial Assistance Needs (comma or new line separated)</label>
                        <textarea name="needs[financial_assistance_needs]" rows="2" class="field-textarea">{{ old('needs.financial_assistance_needs', is_array($student->needsAssessment->financial_assistance_needs ?? null) ? implode(", ", $student->needsAssessment->financial_assistance_needs) : ($student->needsAssessment->financial_assistance_needs ?? '')) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Personal / Social Needs (comma or new line separated)</label>
                        <textarea name="needs[personal_social_needs]" rows="2" class="field-textarea">{{ old('needs.personal_social_needs', is_array($student->needsAssessment->personal_social_needs ?? null) ? implode(", ", $student->needsAssessment->personal_social_needs) : ($student->needsAssessment->personal_social_needs ?? '')) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Stress Responses (comma or new line separated)</label>
                        <textarea name="needs[stress_responses]" rows="2" class="field-textarea">{{ old('needs.stress_responses', is_array($student->needsAssessment->stress_responses ?? null) ? implode(", ", $student->needsAssessment->stress_responses) : ($student->needsAssessment->stress_responses ?? '')) }}</textarea>
                    </div>
                    <div>
                        <label class="field-label">Easy Discussion Target</label>
                        <select name="needs[easy_discussion_target]" class="field-select">
                            <option value="">Select</option>
                            @foreach(['guidance counselor','parents','teachers','brothers/sisters','friends/relatives','nobody','others'] as $edt)
                                <option value="{{ $edt }}" {{ old('needs.easy_discussion_target', $student->needsAssessment->easy_discussion_target ?? '') === $edt ? 'selected' : '' }}>{{ $edt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Counseling Perceptions (comma or new line separated)</label>
                        <textarea name="needs[counseling_perceptions]" rows="2" class="field-textarea">{{ old('needs.counseling_perceptions', is_array($student->needsAssessment->counseling_perceptions ?? null) ? implode(", ", $student->needsAssessment->counseling_perceptions) : ($student->needsAssessment->counseling_perceptions ?? '')) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4 pt-2 sm:pt-4">
                <a href="{{ route('admin.students') }}" class="secondary-btn w-full sm:w-auto text-center rounded-lg">
                    <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i>Back to Students
                </a>
                <button type="submit" class="primary-btn w-full sm:w-auto rounded-lg">
                    <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection