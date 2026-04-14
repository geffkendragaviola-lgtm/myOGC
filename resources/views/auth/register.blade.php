<x-guest-layout>
<style>
    .reg-overlay {
        position: fixed; inset: 0; z-index: 200;
        background: rgba(15,23,42,0.55);
        backdrop-filter: blur(4px);
        display: flex; align-items: flex-start; justify-content: center;
        padding: 80px 24px 24px;
        overflow-y: auto;
    }
    .reg-modal {
        background: #fff; border-radius: 24px;
        width: 100%; max-width: 860px;
        box-shadow: 0 32px 80px rgba(15,23,42,0.18);
        position: relative;
        animation: slideUp 0.3s ease;
        margin-bottom: 24px;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .reg-modal-close {
        position: absolute; top: 18px; right: 18px;
        width: 32px; height: 32px; border-radius: 8px;
        background: #f1f5f9; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: #64748b; font-size: 14px; transition: background 0.2s;
        z-index: 10;
    }
    .reg-modal-close:hover { background: #e2e8f0; color: #0f172a; }

    /*  keep all existing form-card styles  */
    * { box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; }

    .form-card { border-radius: 24px; padding: 32px; }
    .card-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #eef2f7; }
    .card-header-text h1 { margin: 0 0 4px; font-size: 28px; line-height: 1.15; letter-spacing: -0.03em; font-weight: 800; color: #0f172a; }
    .card-header-text p { margin: 0; font-size: 14px; line-height: 1.6; color: #64748b; }
    .school-badge { display: inline-flex; align-items: center; gap: 8px; background: #fff5f5; border: 1px solid #fde0e0; border-radius: 999px; padding: 8px 14px; font-size: 12px; font-weight: 800; color: #820000; letter-spacing: 0.05em; text-transform: uppercase; flex-shrink: 0; }
    .steps-header { margin-bottom: 24px; }
    .steps-title { font-size: 14px; font-weight: 700; color: #334155; margin-bottom: 10px; }
    .step-pills { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
    .step-pill { padding: 8px 14px; border-radius: 999px; font-size: 13px; font-weight: 700; background: #f1f5f9; color: #94a3b8; border: 1px solid transparent; transition: all 0.2s ease; cursor: pointer; }
    .step-pill.active { background: #fff5f5; color: #820000; border-color: #fde0e0; }
    .step-pill.done { background: #effaf3; color: #15803d; border-color: #ccebd6; }
    .progress-bar-wrap { height: 7px; background: #eef2f7; border-radius: 999px; overflow: hidden; }
    .progress-bar { height: 100%; background: linear-gradient(90deg, #820000, #F8650C); border-radius: 999px; transition: width 0.35s ease; }
    .step-panel { display: none; }
    .step-panel.active { display: block; }
    .section-title { font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
    .section-title-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #fff5f5, #fde0e0); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #820000; flex-shrink: 0; }
    .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .field-grid .span2 { grid-column: 1 / -1; }
    .field-wrap { position: relative; }
    .field-label { display: block; margin-bottom: 8px; font-size: 12px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.08em; }
    .field-icon { position: absolute; left: 16px; top: 43px; width: 16px; height: 16px; color: #94a3b8; pointer-events: none; }
    .auth-input, .auth-select, .auth-textarea { width: 100%; border: 1.5px solid #dbe3ee; border-radius: 14px; background: #f8fbff; font-size: 15px; color: #0f172a; outline: none; transition: 0.2s ease; font-family: 'Inter', sans-serif; }
    .auth-input, .auth-select { min-height: 54px; padding: 0 16px; }
    .auth-input.with-icon { padding-left: 46px; }
    .auth-textarea { min-height: 110px; padding: 14px 16px; resize: vertical; }
    .auth-input:focus, .auth-select:focus, .auth-textarea:focus { border-color: #820000; background: #ffffff; box-shadow: 0 0 0 4px rgba(130,0,0,0.08); }
    .auth-input[readonly] { background: #f1f5f9; color: #64748b; cursor: not-allowed; }
    .verify-card { background: linear-gradient(135deg, #fff8f8, #ffffff); border: 1px solid #fde0e0; border-radius: 20px; padding: 24px; margin-bottom: 18px; }
    .verify-card h2 { margin: 0 0 8px; font-size: 22px; line-height: 1.2; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; }
    .verify-card p { margin: 0 0 18px; color: #64748b; line-height: 1.7; font-size: 14px; }
    .otp-group { display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap; }
    .file-upload-wrap { border: 2px dashed #dbe3ee; border-radius: 16px; padding: 22px; text-align: center; cursor: pointer; transition: border-color 0.2s ease, background 0.2s ease; background: #f8fbff; display: block; }
    .file-upload-wrap:hover { border-color: #820000; background: #fffdfd; }
    .file-upload-wrap input { display: none; }
    .file-upload-icon { color: #94a3b8; }
    .file-upload-text { font-size: 14px; color: #64748b; margin-top: 8px; }
    .nav-btns { display: flex; justify-content: space-between; align-items: center; margin-top: 28px; padding-top: 20px; border-top: 1px solid #eef2f7; }
    .btn-back, .btn-next, .btn-submit { min-height: 52px; padding: 0 20px; border-radius: 14px; font-family: 'Inter', sans-serif; font-size: 15px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.18s ease; }
    .btn-back { border: 1.5px solid #dbe3ee; background: #ffffff; color: #475569; }
    .btn-back:hover { border-color: #b8c4d3; color: #0f172a; }
    .btn-next, .btn-submit { border: none; background: linear-gradient(135deg, #820000 0%, #F8650C 100%); color: #ffffff; box-shadow: 0 14px 28px rgba(130,0,0,0.16); }
    .btn-next:hover, .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 18px 34px rgba(130,0,0,0.22); }
    .alert-success { background: #effaf3; border: 1px solid #ccebd6; color: #15803d; border-radius: 14px; padding: 14px 16px; font-size: 14px; margin-bottom: 16px; }
    .alert-error { background: #fff1f2; border: 1px solid #fecdd3; color: #820000; border-radius: 14px; padding: 14px 16px; font-size: 14px; margin-bottom: 16px; }
    .hidden { display: none !important; }
    .form-note { background: #fff8f8; border: 1px solid #fde0e0; border-radius: 14px; padding: 16px; color: #64748b; font-size: 14px; line-height: 1.7; }
    .form-note strong { color: #820000; }
    .login-row { margin-top: 22px; text-align: center; font-size: 14px; color: #64748b; }
    .login-row a { color: #820000; text-decoration: none; font-weight: 700; }
    .login-row a:hover { text-decoration: underline; }

    @media (max-width: 760px) {
        .reg-overlay { padding: 72px 12px 12px; }
        .form-card { padding: 18px; border-radius: 18px; }
        .card-header { align-items: flex-start; flex-direction: column; }
        .field-grid { grid-template-columns: 1fr; }
        .field-grid .span2 { grid-column: 1; }
        .nav-btns { flex-direction: column; gap: 12px; align-items: stretch; }
        .btn-back, .btn-next, .btn-submit { justify-content: center; width: 100%; }
        .otp-group { flex-direction: column; align-items: stretch; }
    }
</style>

<div class="reg-overlay">
    <div class="reg-modal">
        <a href="/" class="reg-modal-close" title="Back to home"><i class="fas fa-times"></i></a>
<div class="form-card">
                    @php
                        $verifiedEmail = session('registration_email_verified');
                        $pendingEmail = session('registration_email_pending');
                    @endphp

                    <div class="card-header">
                        <svg width="56" height="56" viewBox="0 0 52 52" fill="none">
                            <rect width="52" height="52" rx="14" fill="#fff5f5"/>
                            <path d="M26 10L10 18l16 8 16-8-16-8zM18 26.8v8L26 39l8-4.2v-8L26 31l-8-4.2z" fill="#F00000" opacity=".15"/>
                            <path d="M26 10L10 18l16 8 16-8-16-8z" fill="#F00000"/>
                            <path d="M18 26.8v8L26 39l8-4.2v-8L26 31l-8-4.2z" fill="#F00000"/>
                            <path d="M42 18v8" stroke="#F00000" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="42" cy="28" r="2" fill="#F00000"/>
                        </svg>

                        <div class="card-header-text">
                            <h1>Student Registration</h1>
                            <p>Complete all required sections to create your account.</p>
                        </div>

                        <div style="margin-left:auto;">
                            <div class="school-badge">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                                </svg>
                                MSU-IIT
                            </div>
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="alert-success">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert-error">
                            <ul style="margin:0;padding-left:1.2rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (!$verifiedEmail)
                        <div class="verify-card">
                            <h2>Verify Your MSU-IIT Email</h2>
                            <p>Enter your <strong>@g.msuiit.edu.ph</strong> email address to receive a 6-digit verification code.</p>

                            <form method="POST" action="{{ route('register.email.send') }}">
                                @csrf
                                <div class="field-wrap" style="margin-bottom:16px;">
                                    <label class="field-label">MSU-IIT Email Address</label>
                                    <input
                                        class="auth-input"
                                        type="email"
                                        name="email"
                                        value="{{ old('email', $pendingEmail) }}"
                                        required
                                        placeholder="username@g.msuiit.edu.ph"
                                        pattern="^[a-zA-Z0-9._%+-]+@g\.msuiit\.edu\.ph$"
                                    />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <button type="submit" class="btn-next" style="width:100%;justify-content:center;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <line x1="22" y1="2" x2="11" y2="13"/>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                    </svg>
                                    Send Verification Code
                                </button>
                            </form>
                            <div class="login-row">
                                Already have an account?
                                <a href="{{ route('login') }}">Log in here</a>
                            </div>
                        </div>

                        @if ($pendingEmail)
                            <div style="background:#f8fbff;border:1px solid #dbe3ee;border-radius:18px;padding:20px;">
                                <p style="font-size:14px;color:#475569;margin:0 0 14px;line-height:1.7;">
                                    Enter the 6-digit code sent to <strong>{{ $pendingEmail }}</strong>.
                                </p>

                                <form method="POST" action="{{ route('register.email.verify') }}">
                                    @csrf
                                    <div class="otp-group">
                                        <div class="field-wrap" style="flex:1;min-width:220px;">
                                            <label class="field-label">Verification Code</label>
                                            <input
                                                class="auth-input"
                                                style="text-align:center;letter-spacing:0.28em;font-size:18px;font-weight:800;padding-left:16px;"
                                                type="text"
                                                name="code"
                                                inputmode="numeric"
                                                maxlength="6"
                                                placeholder="• • • • • •"
                                                required
                                            />
                                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                                        </div>

                                        <button type="submit" class="btn-next">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                            Verify
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @else
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="regForm">
                            @csrf

                            <div class="steps-header">
                                <div class="steps-title">Registration Progress</div>
                                <div class="step-pills" id="stepPills"></div>
                                <div class="progress-bar-wrap">
                                    <div class="progress-bar" id="progressBar" style="width:0%"></div>
                                </div>
                            </div>

                            <div class="step-panel active" data-step="1" data-title="Basic Info">
                                <div class="section-title">
                                    <div class="section-title-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                    Basic Information
                                </div>

                                <div class="field-grid">
                                    <div class="field-wrap">
                                        <label class="field-label">First Name *</label>
                                        <input class="auth-input" type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="Juan" />
                                        <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Middle Name</label>
                                        <input class="auth-input" type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Optional" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Last Name *</label>
                                        <input class="auth-input" type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Dela Cruz" />
                                        <x-input-error :messages="$errors->get('last_name')" class="mt-1" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Nickname</label>
                                        <input class="auth-input" type="text" name="nickname" value="{{ old('nickname') }}" placeholder="e.g. Juan" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Birthdate</label>
                                        <input class="auth-input" type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Age (auto)</label>
                                        <input class="auth-input" type="number" name="age" id="age" value="{{ old('age') }}" readonly placeholder="Auto-calculated" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Sex</label>
                                        <select class="auth-select" name="sex">
                                            <option value="">Select</option>
                                            <option value="male" {{ old('sex')=='male'?'selected':'' }}>Male</option>
                                            <option value="female" {{ old('sex')=='female'?'selected':'' }}>Female</option>
                                            <option value="other" {{ old('sex')=='other'?'selected':'' }}>Other</option>
                                        </select>
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Civil Status</label>
                                        <select class="auth-select" name="civil_status" id="civil_status">
                                            <option value="">Select</option>
                                            <option value="single" {{ old('civil_status')=='single'?'selected':'' }}>Single</option>
                                            <option value="married" {{ old('civil_status')=='married'?'selected':'' }}>Married</option>
                                            <option value="not legally married" {{ old('civil_status')=='not legally married'?'selected':'' }}>Not Legally Married</option>
                                            <option value="divorced" {{ old('civil_status')=='divorced'?'selected':'' }}>Divorced</option>
                                            <option value="widowed" {{ old('civil_status')=='widowed'?'selected':'' }}>Widowed</option>
                                            <option value="separated" {{ old('civil_status')=='separated'?'selected':'' }}>Separated</option>
                                            <option value="others" {{ old('civil_status')=='others'?'selected':'' }}>Others</option>
                                        </select>
                                    </div>

                                    <div class="field-wrap {{ old('civil_status') != 'others' ? 'hidden' : '' }}" id="civil_status_other_container">
                                        <label class="field-label">Specify Civil Status</label>
                                        <input class="auth-input" type="text" name="civil_status_other" value="{{ old('civil_status_other') }}" placeholder="Please specify" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">No. of Children</label>
                                        <input class="auth-input" type="number" name="number_of_children" value="{{ old('number_of_children', 0) }}" min="0" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Citizenship</label>
                                        <input class="auth-input" type="text" name="citizenship" value="{{ old('citizenship') }}" placeholder="e.g. Filipino" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Birthplace</label>
                                        <input class="auth-input" type="text" name="birthplace" value="{{ old('birthplace') }}" placeholder="City, Province" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Religion</label>
                                        <input class="auth-input" type="text" name="religion" value="{{ old('religion') }}" placeholder="e.g. Roman Catholic" />
                                    </div>

                                    <div class="field-wrap span2">
                                        <label class="field-label">Address in Iligan City *</label>
                                        <textarea class="auth-textarea" name="address" placeholder="House No., Street, Barangay, Iligan City">{{ old('address') }}</textarea>
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Phone Number *</label>
                                        <input class="auth-input" type="text" name="phone_number" value="{{ old('phone_number') }}" required placeholder="09XXXXXXXXX" inputmode="numeric" maxlength="11" minlength="11" pattern="^09\d{9}$" />
                                        <x-input-error :messages="$errors->get('phone_number')" class="mt-1" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">MSU-IIT Email (verified)</label>
                                        <input class="auth-input" type="email" name="email_display" value="{{ $verifiedEmail }}" readonly />
                                        <input type="hidden" name="email" value="{{ $verifiedEmail }}">
                                    </div>
                                </div>

                                <div class="nav-btns">
                                    <span></span>
                                    <button type="button" class="btn-next step-next">
                                        Next: School Data
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="9 18 15 12 9 6"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="step-panel" data-step="2" data-title="School Data">
                                <div class="section-title">
                                    <div class="section-title-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
                                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                        </svg>
                                    </div>
                                    School Data
                                </div>

                                <div class="field-grid">
                                    <div class="field-wrap">
                                        <label class="field-label">Student ID *</label>
                                        <input class="auth-input" type="text" name="student_id" value="{{ old('student_id') }}" required placeholder="e.g. 2024-XXXXX" />
                                        <x-input-error :messages="$errors->get('student_id')" class="mt-1" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Year Level *</label>
                                        <select class="auth-select" name="year_level" id="year_level" required>
                                            <option value="">Select Year Level</option>
                                            @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year'] as $yr)
                                                <option value="{{ $yr }}" {{ old('year_level')==$yr?'selected':'' }}>{{ $yr }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="field-wrap hidden" id="initialInterviewCompletedWrapper">
                                        <label class="field-label">Initial Interview Completion</label>
                                        <select class="auth-select" name="initial_interview_completed">
                                            <option value="">Select</option>
                                            <option value="yes" {{ old('initial_interview_completed')=='yes'?'selected':'' }}>Yes, completed</option>
                                            <option value="no" {{ old('initial_interview_completed')=='no'?'selected':'' }}>No, not yet</option>
                                        </select>
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Course *</label>
                                        <input class="auth-input" type="text" name="course" value="{{ old('course') }}" required placeholder="e.g. BS Computer Science" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">College *</label>
                                        <select class="auth-select" name="college_id" required>
                                            <option value="">Select College</option>
                                            @foreach($colleges as $college)
                                                <option value="{{ $college->id }}" {{ old('college_id')==$college->id?'selected':'' }}>{{ $college->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">MSU-SASE Score</label>
                                        <input class="auth-input" type="number" step="0.01" name="msu_sase_score" value="{{ old('msu_sase_score') }}" placeholder="e.g. 85.50" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Academic Year</label>
                                        <input class="auth-input" type="text" name="academic_year" value="{{ old('academic_year') }}" placeholder="e.g. 2024-2025" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Student Status *</label>
                                        <select class="auth-select" name="student_status" required>
                                            <option value="">Select Status</option>
                                            @foreach(['new'=>'New','transferee'=>'Transferee','returnee'=>'Returnee','shiftee'=>'Shiftee'] as $v=>$l)
                                                <option value="{{ $v }}" {{ old('student_status')==$v?'selected':'' }}>{{ $l }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="field-wrap span2">
                                        <label class="field-label">Profile Picture</label>
                                        <label class="file-upload-wrap" for="profile_picture">
                                            <svg class="file-upload-icon" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                                <polyline points="21 15 16 10 5 21"/>
                                            </svg>
                                            <div class="file-upload-text">Click to upload profile photo (JPG, PNG)</div>
                                            <input id="profile_picture" type="file" name="profile_picture" accept="image/*" />
                                        </label>
                                    </div>
                                </div>

                                <div class="nav-btns">
                                    <button type="button" class="btn-back step-back">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="15 18 9 12 15 6"/>
                                        </svg>
                                        Back
                                    </button>

                                    <button type="button" class="btn-next step-next">
                                        Next: Personal Data
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="9 18 15 12 9 6"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="step-panel" data-step="3" data-title="Personal Data">
                                <div class="section-title">
                                    <div class="section-title-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                            <polyline points="9 22 9 12 15 12 15 22"/>
                                        </svg>
                                    </div>
                                    Personal Data
                                </div>

                                <div class="field-grid">
                                    <div class="field-wrap span2">
                                        <label class="field-label">Home Address</label>
                                        <textarea class="auth-textarea" name="home_address" placeholder="Complete home address">{{ old('home_address') }}</textarea>
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Region of Residence</label>
                                        <select class="auth-select" name="region_of_residence">
                                            <option value="">Select Region</option>
                                            @foreach(['National Capital Region (NCR) – Metro Manila','Region I – Ilocos Region','Region II – Cagayan Valley','Region III – Central Luzon','Region IV-A – CALABARZON','Region IV-B – MIMAROPA','Region V – Bicol Region','Cordillera Administrative Region (CAR)','Region VI – Western Visayas','Region VII – Central Visayas','Region VIII – Eastern Visayas','Region IX – Zamboanga Peninsula','Region X – Northern Mindanao','Region XI – Davao Region','Region XII – SOCCSKSARGEN','Region XIII – Caraga','Bangsamoro Autonomous Region in Muslim Mindanao (BARMM).'] as $r)
                                                <option value="{{ $r }}" {{ old('region_of_residence')==$r?'selected':'' }}>{{ $r }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Stays With</label>
                                        <select class="auth-select" name="stays_with">
                                            <option value="">Select</option>
                                            @foreach(['parents/guardian'=>'Parents/Guardian','board/roommates'=>'Board/Roommates','relatives'=>'Relatives','friends'=>'Friends','employer'=>'Employer','living on my own'=>'Living on my own'] as $v=>$l)
                                                <option value="{{ $v }}" {{ old('stays_with')==$v?'selected':'' }}>{{ $l }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Working Student</label>
                                        <select class="auth-select" name="working_student">
                                            <option value="">Select</option>
                                            <option value="yes full time" {{ old('working_student')=='yes full time'?'selected':'' }}>Yes, Full-time</option>
                                            <option value="yes part time" {{ old('working_student')=='yes part time'?'selected':'' }}>Yes, Part-time</option>
                                            <option value="no but planning to work" {{ old('working_student')=='no but planning to work'?'selected':'' }}>No, planning to work</option>
                                            <option value="no and have no plan to work" {{ old('working_student')=='no and have no plan to work'?'selected':'' }}>No plans to work</option>
                                        </select>
                                    </div>

                                    <div class="field-wrap span2">
                                        <label class="field-label">Talents / Skills (comma-separated)</label>
                                        <textarea class="auth-textarea" name="talents_skills" placeholder="e.g. singing, programming, basketball">{{ old('talents_skills') }}</textarea>
                                    </div>
                                </div>

                                <div class="nav-btns">
                                    <button type="button" class="btn-back step-back">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="15 18 9 12 15 6"/>
                                        </svg>
                                        Back
                                    </button>

                                    <button type="button" class="btn-next step-next">
                                        Next: Account Setup
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="9 18 15 12 9 6"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="step-panel" data-step="4" data-title="Account Setup">
                                <div class="section-title">
                                    <div class="section-title-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                                        </svg>
                                    </div>
                                    Account Setup
                                </div>

                                <div class="field-grid">
                                    <div class="field-wrap">
                                        <label class="field-label">Password *</label>
                                        <input class="auth-input" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                                    </div>

                                    <div class="field-wrap">
                                        <label class="field-label">Confirm Password *</label>
                                        <input class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat password" />
                                    </div>

                                    <div class="field-wrap span2">
                                        <div class="form-note">
                                            <strong>Password requirements:</strong> Use at least 8 characters with letters and numbers. By registering, you agree to the terms and use of the MSU-IIT Guidance and Counseling Portal.
                                        </div>
                                    </div>
                                </div>

                                <div class="nav-btns">
                                    <button type="button" class="btn-back step-back">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="15 18 9 12 15 6"/>
                                        </svg>
                                        Back
                                    </button>

                                    <button type="submit" class="btn-submit">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        Complete Registration
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const panels = Array.from(document.querySelectorAll('.step-panel'));
    const pillsContainer = document.getElementById('stepPills');
    const progressBar = document.getElementById('progressBar');
    let current = 0;

    function buildPills() {
        if (!pillsContainer || panels.length === 0) return;
        pillsContainer.innerHTML = '';

        panels.forEach((p, i) => {
            const pill = document.createElement('span');
            pill.className = 'step-pill' + (i === current ? ' active' : '') + (i < current ? ' done' : '');
            pill.textContent = (i + 1) + '. ' + (p.dataset.title || '');
            pill.addEventListener('click', () => {
                if (i <= current) goTo(i);
            });
            pillsContainer.appendChild(pill);
        });

        if (progressBar) {
            progressBar.style.width = panels.length > 1 ? ((current / (panels.length - 1)) * 100) + '%' : '0%';
        }
    }

    function goTo(idx) {
        if (!panels[idx]) return;
        panels[current].classList.remove('active');
        current = idx;
        panels[current].classList.add('active');
        buildPills();

        const rightPanel = document.querySelector('.auth-right');
        if (rightPanel) {
            rightPanel.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    document.querySelectorAll('.step-next').forEach(btn => {
        btn.addEventListener('click', () => {
            if (current < panels.length - 1) goTo(current + 1);
        });
    });

    document.querySelectorAll('.step-back').forEach(btn => {
        btn.addEventListener('click', () => {
            if (current > 0) goTo(current - 1);
        });
    });

    buildPills();

    const bdInput = document.getElementById('birthdate');
    const ageInput = document.getElementById('age');
    if (bdInput && ageInput) {
        bdInput.addEventListener('change', function() {
            const bd = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - bd.getFullYear();
            const m = today.getMonth() - bd.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < bd.getDate())) age--;
            ageInput.value = isNaN(age) ? '' : age;
        });
    }

    const csSelect = document.getElementById('civil_status');
    const csOther = document.getElementById('civil_status_other_container');
    if (csSelect && csOther) {
        csSelect.addEventListener('change', function() {
            csOther.classList.toggle('hidden', this.value !== 'others');
        });
    }

    const ylSelect = document.getElementById('year_level');
    const iiWrapper = document.getElementById('initialInterviewCompletedWrapper');
    if (ylSelect && iiWrapper) {
        ylSelect.addEventListener('change', function() {
            iiWrapper.classList.toggle('hidden', this.value !== '2nd Year');
        });
    }

    const fileInput = document.getElementById('profile_picture');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const label = this.closest('.file-upload-wrap').querySelector('.file-upload-text');
            if (label && this.files[0]) label.textContent = '✓ ' + this.files[0].name;
        });
    }
});
</script>
    </div>
</div>
</x-guest-layout>
