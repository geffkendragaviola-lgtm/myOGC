<x-guest-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap');

    .auth-wrapper {
        font-family: 'Nunito', sans-serif;
        min-height: 100vh;
        background: linear-gradient(145deg, #f0f4ff 0%, #e8f0fe 40%, #fce8e8 100%);
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 2rem 1rem 4rem;
        position: relative;
        overflow-x: hidden;
    }

    .auth-wrapper::before {
        content: '';
        position: fixed;
        top: -60px; right: -60px;
        width: 320px; height: 320px;
        background: radial-gradient(circle, rgba(240,0,0,0.07) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .auth-card {
        background: #fff;
        border-radius: 28px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.10), 0 4px 16px rgba(0,0,0,0.06);
        width: 100%;
        max-width: 780px;
        padding: 2.5rem;
        position: relative;
        z-index: 1;
        animation: cardIn 0.5s cubic-bezier(.22,1,.36,1) both;
    }

    @keyframes cardIn {
        from { opacity:0; transform: translateY(32px) scale(0.97); }
        to   { opacity:1; transform: translateY(0) scale(1); }
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1.5px solid #f0f0f0;
    }

    .card-header-text h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.45rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 0.2rem;
    }

    .card-header-text p {
        font-size: 0.82rem;
        color: #888;
        margin: 0;
    }

    .school-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: linear-gradient(135deg, #fff5f5, #fff);
        border: 1.5px solid #fde0e0;
        border-radius: 20px;
        padding: 0.3rem 0.85rem;
        font-size: 0.72rem;
        font-weight: 800;
        color: #F00000;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    /* Steps */
    .steps-header { margin-bottom: 1.5rem; }
    .steps-title { font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 0.92rem; color: #444; margin-bottom: 0.75rem; }

    .step-pills { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem; }
    .step-pill {
        padding: 0.3rem 0.85rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        background: #f0f0f0;
        color: #aaa;
        cursor: pointer;
        transition: all 0.2s;
        border: 1.5px solid transparent;
    }
    .step-pill.active { background: #fff5f5; color: #F00000; border-color: #fde0e0; }
    .step-pill.done { background: #e8f5e9; color: #388e3c; border-color: #c8e6c9; }

    .progress-bar-wrap { height: 5px; background: #eee; border-radius: 10px; overflow: hidden; }
    .progress-bar { height: 100%; background: linear-gradient(90deg, #F00000, #ff6b6b); border-radius: 10px; transition: width 0.4s ease; }

    /* Step panels */
    .step-panel { display: none; }
    .step-panel.active { display: block; }

    .section-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title-icon {
        width: 32px; height: 32px;
        background: linear-gradient(135deg, #fff5f5, #fde0e0);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: #F00000;
        flex-shrink: 0;
    }

    .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .field-grid .span2 { grid-column: 1 / -1; }

    .field-wrap { position: relative; }

    .field-label {
        font-size: 0.73rem;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.35rem;
        display: block;
    }

    .field-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #ccc;
        width: 16px; height: 16px;
        pointer-events: none;
    }

    .auth-input, .auth-select, .auth-textarea {
        width: 100%;
        padding: 0.68rem 0.9rem 0.68rem 2.4rem;
        border: 1.5px solid #e8e8e8;
        border-radius: 10px;
        font-size: 0.88rem;
        font-family: 'Nunito', sans-serif;
        color: #333;
        background: #fafafa;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        box-sizing: border-box;
    }

    .auth-select { padding-left: 0.9rem; }
    .auth-textarea { padding-left: 0.9rem; min-height: 80px; resize: vertical; }

    .auth-input:focus, .auth-select:focus, .auth-textarea:focus {
        border-color: #F00000;
        box-shadow: 0 0 0 3px rgba(240,0,0,0.09);
        background: #fff;
    }

    .auth-input[readonly] { background: #f5f5f5; color: #888; cursor: not-allowed; }

    .radio-group { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .radio-pill {
        display: flex; align-items: center; gap: 0.4rem;
        padding: 0.38rem 0.85rem;
        border: 1.5px solid #e8e8e8;
        border-radius: 20px;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.2s;
        background: #fafafa;
    }
    .radio-pill input { accent-color: #F00000; }
    .radio-pill:has(input:checked) { border-color: #F00000; background: #fff5f5; color: #F00000; font-weight: 700; }

    .checkbox-grid { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .checkbox-pill {
        display: flex; align-items: center; gap: 0.4rem;
        padding: 0.35rem 0.8rem;
        border: 1.5px solid #e8e8e8;
        border-radius: 20px;
        font-size: 0.8rem;
        cursor: pointer;
        background: #fafafa;
        transition: all 0.2s;
    }
    .checkbox-pill input { accent-color: #F00000; }
    .checkbox-pill:has(input:checked) { border-color: #F00000; background: #fff5f5; color: #F00000; font-weight: 700; }

    .nav-btns { display: flex; justify-content: space-between; align-items: center; margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1.5px solid #f0f0f0; }

    .btn-back {
        padding: 0.7rem 1.4rem;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        background: #fff;
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: #666;
        cursor: pointer;
        display: flex; align-items: center; gap: 0.4rem;
        transition: all 0.15s;
    }
    .btn-back:hover { border-color: #bbb; color: #333; }

    .btn-next {
        padding: 0.7rem 1.6rem;
        background: linear-gradient(135deg, #F00000 0%, #c20000 100%);
        border: none;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.88rem;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        display: flex; align-items: center; gap: 0.5rem;
        box-shadow: 0 4px 14px rgba(240,0,0,0.25);
        transition: transform 0.15s, box-shadow 0.2s;
    }
    .btn-next:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(240,0,0,0.32); }

    .btn-submit {
        padding: 0.78rem 2rem;
        background: linear-gradient(135deg, #F00000 0%, #c20000 100%);
        border: none;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        display: flex; align-items: center; gap: 0.5rem;
        box-shadow: 0 4px 18px rgba(240,0,0,0.28);
        transition: transform 0.15s, box-shadow 0.2s;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(240,0,0,0.38); }

    .verify-card {
        background: linear-gradient(135deg, #fff5f5, #fff);
        border: 1.5px solid #fde0e0;
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
    }

    .verify-card h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 0.3rem;
    }

    .verify-card p {
        font-size: 0.83rem;
        color: #888;
        margin: 0 0 1.25rem;
    }

    .otp-group { display: flex; gap: 0.6rem; align-items: flex-end; flex-wrap: wrap; }

    .file-upload-wrap {
        border: 2px dashed #e0e0e0;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s;
        background: #fafafa;
    }
    .file-upload-wrap:hover { border-color: #F00000; }
    .file-upload-wrap input { display: none; }
    .file-upload-text { font-size: 0.83rem; color: #999; margin-top: 0.4rem; }
    .file-upload-icon { color: #ddd; }

    .hidden { display: none !important; }

    .alert-success {
        background: #e8f5e9; border: 1px solid #c8e6c9;
        color: #2e7d32; border-radius: 10px;
        padding: 0.75rem 1rem; font-size: 0.85rem; margin-bottom: 1rem;
    }

    .alert-error {
        background: #ffebee; border: 1px solid #ffcdd2;
        color: #c62828; border-radius: 10px;
        padding: 0.75rem 1rem; font-size: 0.85rem; margin-bottom: 1rem;
    }

    @media (max-width: 640px) {
        .auth-card { padding: 1.5rem; }
        .field-grid { grid-template-columns: 1fr; }
        .field-grid .span2 { grid-column: 1; }
    }
</style>

<div class="auth-wrapper">
<div class="auth-card">

    @php
        $verifiedEmail = session('registration_email_verified');
        $pendingEmail = session('registration_email_pending');
    @endphp

    <!-- Card Header -->
    <div class="card-header">
        <svg width="52" height="52" viewBox="0 0 52 52" fill="none">
            <rect width="52" height="52" rx="14" fill="#fff5f5"/>
            <path d="M26 10L10 18l16 8 16-8-16-8zM18 26.8v8L26 39l8-4.2v-8L26 31l-8-4.2z" fill="#F00000" opacity=".15"/>
            <path d="M26 10L10 18l16 8 16-8-16-8z" fill="#F00000"/>
            <path d="M18 26.8v8L26 39l8-4.2v-8L26 31l-8-4.2z" fill="#F00000"/>
            <path d="M42 18v8" stroke="#F00000" stroke-width="2" stroke-linecap="round"/>
            <circle cx="42" cy="28" r="2" fill="#F00000"/>
        </svg>
        <div class="card-header-text">
            <h1>Student Registration</h1>
            <p>MSU-IIT Guidance & Counseling Portal — Complete all sections to register</p>
        </div>
        <div style="margin-left:auto">
            <div class="school-badge">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
                MSU-IIT
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <ul style="margin:0;padding-left:1.2rem">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!$verifiedEmail)
    <!-- STEP: Email Verification -->
    <div class="verify-card">
        <h2>
            <svg style="display:inline;vertical-align:middle;margin-right:6px" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F00000" stroke-width="2.5"><path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            Verify Your MSU-IIT Email
        </h2>
        <p>Enter your @g.msuiit.edu.ph email to receive a 6-digit verification code.</p>

        <form method="POST" action="{{ route('register.email.send') }}">
            @csrf
            <div class="field-wrap" style="margin-bottom:1rem">
                <label class="field-label">MSU-IIT Email Address</label>
                <input class="auth-select" style="padding-left:0.9rem" type="email" name="email"
                       value="{{ old('email', $pendingEmail) }}" required placeholder="username@g.msuiit.edu.ph"
                       pattern="^[a-zA-Z0-9._%+-]+@g\.msuiit\.edu\.ph$" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <button type="submit" class="btn-next" style="width:100%;justify-content:center">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Send Verification Code
            </button>
        </form>
    </div>

    @if ($pendingEmail)
    <div style="background:#f9f9f9;border:1.5px solid #eee;border-radius:16px;padding:1.5rem">
        <p style="font-size:0.85rem;color:#666;margin:0 0 1rem">
            Enter the 6-digit code sent to <strong>{{ $pendingEmail }}</strong>
        </p>
        <form method="POST" action="{{ route('register.email.verify') }}">
            @csrf
            <div class="otp-group">
                <div class="field-wrap" style="flex:1;min-width:180px">
                    <label class="field-label">Verification Code</label>
                    <input class="auth-select" style="padding-left:0.9rem;letter-spacing:0.3em;font-size:1.1rem;font-weight:700;text-align:center"
                           type="text" name="code" inputmode="numeric" maxlength="6" placeholder="• • • • • •" required />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>
                <button type="submit" class="btn-next">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Verify
                </button>
            </div>
        </form>
    </div>
    @endif

    @else
    <!-- REGISTRATION FORM WITH STEPS -->
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="regForm">
        @csrf

        <!-- Step indicator -->
        <div class="steps-header">
            <div class="steps-title">Registration Progress</div>
            <div class="step-pills" id="stepPills"></div>
            <div class="progress-bar-wrap">
                <div class="progress-bar" id="progressBar" style="width:0%"></div>
            </div>
        </div>

        <!-- ===== STEP 1: Basic Information ===== -->
        <div class="step-panel active" data-step="1" data-title="Basic Info">
            <div class="section-title">
                <div class="section-title-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
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
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>

        <!-- ===== STEP 2: School Data ===== -->
        <div class="step-panel" data-step="2" data-title="School Data">
            <div class="section-title">
                <div class="section-title-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
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
                        <svg class="file-upload-icon" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <div class="file-upload-text">Click to upload profile photo (JPG, PNG)</div>
                        <input id="profile_picture" type="file" name="profile_picture" accept="image/*" />
                    </label>
                </div>
            </div>
            <div class="nav-btns">
                <button type="button" class="btn-back step-back">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    Back
                </button>
                <button type="button" class="btn-next step-next">
                    Next: Personal Data
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>

        <!-- ===== STEP 3: Personal Data ===== -->
        <div class="step-panel" data-step="3" data-title="Personal Data">
            <div class="section-title">
                <div class="section-title-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
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
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    Back
                </button>
                <button type="button" class="btn-next step-next">
                    Next: Account Setup
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>

        <!-- ===== STEP 4: Account Setup (Password) ===== -->
        <div class="step-panel" data-step="4" data-title="Account Setup">
            <div class="section-title">
                <div class="section-title-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
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
                <div class="field-wrap span2" style="background:#fff8f8;border:1.5px solid #fde0e0;border-radius:12px;padding:1rem">
                    <div style="font-size:0.8rem;color:#888;line-height:1.6">
                        <strong style="color:#F00000">Password requirements:</strong> At least 8 characters, including letters and numbers. By registering, you agree to the MSU-IIT Guidance Portal terms of use.
                    </div>
                </div>
            </div>
            <div class="nav-btns">
                <button type="button" class="btn-back step-back">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    Back
                </button>
                <button type="submit" class="btn-submit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Complete Registration
                </button>
            </div>
        </div>

    </form>
    @endif

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Multi-step logic
    const panels = Array.from(document.querySelectorAll('.step-panel'));
    const pillsContainer = document.getElementById('stepPills');
    const progressBar = document.getElementById('progressBar');
    let current = 0;

    function buildPills() {
        if (!pillsContainer) return;
        pillsContainer.innerHTML = '';
        panels.forEach((p, i) => {
            const pill = document.createElement('span');
            pill.className = 'step-pill' + (i === current ? ' active' : '') + (i < current ? ' done' : '');
            pill.textContent = (i + 1) + '. ' + (p.dataset.title || '');
            pill.addEventListener('click', () => { if (i <= current) goTo(i); });
            pillsContainer.appendChild(pill);
        });
        if (progressBar) {
            progressBar.style.width = ((current / (panels.length - 1)) * 100) + '%';
        }
    }

    function goTo(idx) {
        panels[current].classList.remove('active');
        current = idx;
        panels[current].classList.add('active');
        buildPills();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.querySelectorAll('.step-next').forEach(btn => {
        btn.addEventListener('click', () => { if (current < panels.length - 1) goTo(current + 1); });
    });
    document.querySelectorAll('.step-back').forEach(btn => {
        btn.addEventListener('click', () => { if (current > 0) goTo(current - 1); });
    });

    buildPills();

    // Age from birthdate
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

    // Civil status other
    const csSelect = document.getElementById('civil_status');
    const csOther = document.getElementById('civil_status_other_container');
    if (csSelect && csOther) {
        csSelect.addEventListener('change', function() {
            csOther.classList.toggle('hidden', this.value !== 'others');
        });
    }

    // Year level - show initial interview for 2nd year
    const ylSelect = document.getElementById('year_level');
    const iiWrapper = document.getElementById('initialInterviewCompletedWrapper');
    if (ylSelect && iiWrapper) {
        ylSelect.addEventListener('change', function() {
            iiWrapper.classList.toggle('hidden', this.value !== '2nd Year');
        });
    }

    // File upload label update
    const fileInput = document.getElementById('profile_picture');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const label = this.closest('.file-upload-wrap').querySelector('.file-upload-text');
            if (label && this.files[0]) label.textContent = '✓ ' + this.files[0].name;
        });
    }
});
</script>
</x-guest-layout>