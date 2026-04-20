<x-guest-layout>
<style>
.reg-overlay{position:fixed;inset:0;z-index:200;background:rgba(15,23,42,0.55);backdrop-filter:blur(4px);display:flex;align-items:flex-start;justify-content:center;padding:80px 24px 24px;overflow-y:auto}
.reg-modal{background:#fff;border-radius:24px;width:100%;max-width:860px;box-shadow:0 32px 80px rgba(15,23,42,0.18);position:relative;animation:slideUp 0.3s ease;margin-bottom:24px}
@keyframes slideUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.reg-modal-close{position:absolute;top:18px;right:18px;width:32px;height:32px;border-radius:8px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:14px;transition:background 0.2s;z-index:10}
.reg-modal-close:hover{background:#e2e8f0;color:#0f172a}
*{box-sizing:border-box}
body{font-family:'Inter',sans-serif}
.form-card{border-radius:24px;padding:32px}
.card-header{display:flex;align-items:center;gap:16px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #eef2f7}
.card-header-text h1{margin:0 0 4px;font-size:28px;line-height:1.15;letter-spacing:-0.03em;font-weight:800;color:#0f172a}
.card-header-text p{margin:0;font-size:14px;line-height:1.6;color:#64748b}
.school-badge{display:inline-flex;align-items:center;gap:8px;background:#fff5f5;border:1px solid #fde0e0;border-radius:999px;padding:8px 14px;font-size:12px;font-weight:800;color:#820000;letter-spacing:0.05em;text-transform:uppercase;flex-shrink:0}
.steps-header{margin-bottom:24px}
.steps-title{font-size:14px;font-weight:700;color:#334155;margin-bottom:10px}
.step-pills{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px}
.step-pill{padding:8px 14px;border-radius:999px;font-size:13px;font-weight:700;background:#f1f5f9;color:#94a3b8;border:1px solid transparent;transition:all 0.2s ease;cursor:pointer}
.step-pill.active{background:#fff5f5;color:#820000;border-color:#fde0e0}
.step-pill.done{background:#effaf3;color:#15803d;border-color:#ccebd6}
.progress-bar-wrap{height:7px;background:#eef2f7;border-radius:999px;overflow:hidden}
.progress-bar{height:100%;background:linear-gradient(90deg,#820000,#F8650C);border-radius:999px;transition:width 0.35s ease}
.step-panel{display:none}
.step-panel.active{display:block}
.section-title{font-size:20px;font-weight:800;color:#0f172a;margin-bottom:20px;display:flex;align-items:center;gap:10px;letter-spacing:-0.02em}
.section-title-icon{width:36px;height:36px;background:linear-gradient(135deg,#fff5f5,#fde0e0);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#820000;flex-shrink:0}
.field-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.field-grid .span2{grid-column:1/-1}
.field-wrap{position:relative}
.field-label{display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#334155;text-transform:uppercase;letter-spacing:0.08em}
.auth-input,.auth-select,.auth-textarea{width:100%;border:1.5px solid #dbe3ee;border-radius:14px;background:#f8fbff;font-size:15px;color:#0f172a;outline:none;transition:0.2s ease;font-family:'Inter',sans-serif}
.auth-input,.auth-select{min-height:54px;padding:0 16px}
.auth-textarea{min-height:110px;padding:14px 16px;resize:vertical}
.auth-input:focus,.auth-select:focus,.auth-textarea:focus{border-color:#820000;background:#ffffff;box-shadow:0 0 0 4px rgba(130,0,0,0.08)}
.auth-input[readonly]{background:#f1f5f9;color:#64748b;cursor:not-allowed}
.verify-card{background:linear-gradient(135deg,#fff8f8,#ffffff);border:1px solid #fde0e0;border-radius:20px;padding:24px;margin-bottom:18px}
.verify-card h2{margin:0 0 8px;font-size:22px;line-height:1.2;font-weight:800;color:#0f172a;letter-spacing:-0.02em}
.verify-card p{margin:0 0 18px;color:#64748b;line-height:1.7;font-size:14px}
.otp-group{display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap}
.file-upload-wrap{border:2px dashed #dbe3ee;border-radius:16px;padding:22px;text-align:center;cursor:pointer;transition:border-color 0.2s ease,background 0.2s ease;background:#f8fbff;display:block}
.file-upload-wrap:hover{border-color:#820000;background:#fffdfd}
.file-upload-wrap input{display:none}
.file-upload-icon{color:#94a3b8}
.file-upload-text{font-size:14px;color:#64748b;margin-top:8px}
.nav-btns{display:flex;justify-content:space-between;align-items:center;margin-top:28px;padding-top:20px;border-top:1px solid #eef2f7}
.btn-back,.btn-next,.btn-submit{min-height:52px;padding:0 20px;border-radius:14px;font-family:'Inter',sans-serif;font-size:15px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:0.18s ease}
.btn-back{border:1.5px solid #dbe3ee;background:#ffffff;color:#475569}
.btn-back:hover{border-color:#b8c4d3;color:#0f172a}
.btn-next,.btn-submit{border:none;background:#820000;color:#ffffff;box-shadow:0 14px 28px rgba(130,0,0,0.16)}
.btn-next:hover,.btn-submit:hover{transform:translateY(-1px);box-shadow:0 18px 34px rgba(130,0,0,0.22)}
.alert-error{background:#fff1f2;border:1px solid #fecdd3;color:#820000;border-radius:14px;padding:14px 16px;font-size:14px;margin-bottom:16px}
.hidden{display:none!important}
.login-row{margin-top:22px;text-align:center;font-size:14px;color:#64748b}
.login-row a{color:#820000;text-decoration:none;font-weight:700}
.login-row a:hover{text-decoration:underline}
.check-group{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:8px}
.check-item{display:flex;align-items:center;gap:8px;font-size:14px;color:#334155}
.check-item input[type=checkbox],.check-item input[type=radio]{width:16px;height:16px;accent-color:#820000;flex-shrink:0}
.radio-group{display:flex;flex-wrap:wrap;gap:12px;margin-top:8px}
.radio-item{display:flex;align-items:center;gap:8px;font-size:14px;color:#334155}
.radio-item input[type=radio]{width:16px;height:16px;accent-color:#820000}
.freq-table{width:100%;border-collapse:collapse;font-size:14px;margin-top:8px}
.freq-table th{background:#f8fbff;padding:10px 12px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;border-bottom:1.5px solid #dbe3ee}
.freq-table th:first-child{text-align:left}
.freq-table td{padding:10px 12px;border-bottom:1px solid #eef2f7;vertical-align:middle;text-align:center;color:#334155}
.freq-table td:first-child{text-align:left}
.freq-table tr:last-child td{border-bottom:none}
.freq-table input[type=radio]{width:16px;height:16px;accent-color:#820000}
.table-wrap{overflow-x:auto;border:1.5px solid #dbe3ee;border-radius:14px}
@media (max-width:760px){
.reg-overlay{padding:72px 12px 12px}
.form-card{padding:18px;border-radius:18px}
.card-header{align-items:flex-start;flex-direction:column}
.field-grid{grid-template-columns:1fr}
.field-grid .span2{grid-column:1}
.nav-btns{flex-direction:column;gap:12px;align-items:stretch}
.btn-back,.btn-next,.btn-submit{justify-content:center;width:100%}
.otp-group{flex-direction:column;align-items:stretch}
.check-group{grid-template-columns:1fr}
}
</style>

<div class="reg-overlay">
    <div class="reg-modal">
        <a href="/" class="reg-modal-close" title="Back to home"><i class="fas fa-xmark"></i></a>
        <div class="form-card">
            @php
                $verifiedEmail = session('registration_email_verified');
                $pendingEmail  = session('registration_email_pending');
            @endphp

            <div class="card-header">
                <svg width="56" height="56" viewBox="0 0 52 52" fill="none">
                    <rect width="52" height="52" rx="14" fill="#fff5f5"/>
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
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
                        MSU-IIT
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <ul style="margin:0;padding-left:1.2rem;">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            @if (!$verifiedEmail)
                {{-- EMAIL VERIFICATION --}}
                <div class="verify-card">
                    <h2>Verify Your MSU-IIT Email</h2>
                    <p>Enter your <strong>@g.msuiit.edu.ph</strong> email address to receive a 6-digit verification code.</p>
                    <form method="POST" action="{{ route('register.email.send') }}">
                        @csrf
                        <div class="field-wrap" style="margin-bottom:16px;">
                            <label class="field-label">MSU-IIT Email Address</label>
                            <input class="auth-input" type="email" name="email" value="{{ old('email', $pendingEmail) }}" required placeholder="username@g.msuiit.edu.ph" pattern="^[a-zA-Z0-9._%+-]+@g\.msuiit\.edu\.ph$" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <button type="submit" class="btn-next" style="width:100%;justify-content:center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Send Verification Code
                        </button>
                    </form>
                    <div class="login-row">Already have an account? <a href="{{ route('login') }}">Log in here</a></div>
                </div>

                @if ($pendingEmail)
                    <div style="background:#f8fbff;border:1px solid #dbe3ee;border-radius:18px;padding:20px;">
                        <p style="font-size:14px;color:#475569;margin:0 0 14px;line-height:1.7;">Enter the 6-digit code sent to <strong>{{ $pendingEmail }}</strong>.</p>
                        <form method="POST" action="{{ route('register.email.verify') }}">
                            @csrf
                            <div class="otp-group">
                                <div class="field-wrap" style="flex:1;min-width:220px;">
                                    <label class="field-label">Verification Code</label>
                                    <input class="auth-input" style="text-align:center;letter-spacing:0.28em;font-size:18px;font-weight:800;" type="text" name="code" inputmode="numeric" maxlength="6" placeholder="• • • • • •" required />
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
                {{-- MAIN REGISTRATION FORM --}}
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="regForm">
                    @csrf
                    <div class="steps-header">
                        <div class="steps-title">Registration Progress</div>
                        <div class="step-pills" id="stepPills"></div>
                        <div class="progress-bar-wrap"><div class="progress-bar" id="progressBar" style="width:0%"></div></div>
                    </div>

                    {{-- STEP 1: BASIC INFO --}}
                    <div class="step-panel active" data-step="1" data-title="Basic Info">
                        <div class="section-title">
                            <div class="section-title-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
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
                            <div class="field-wrap {{ old('civil_status')!='others'?'hidden':'' }}" id="civil_status_other_container">
                                <label class="field-label">Specify Civil Status</label>
                                <input class="auth-input" type="text" name="civil_status_other" value="{{ old('civil_status_other') }}" placeholder="Please specify" />
                                <x-input-error :messages="$errors->get('civil_status_other')" class="mt-1" />
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
                                <input class="auth-input" type="text" name="phone_number" value="{{ old('phone_number') }}" required placeholder="09XXXXXXXXX" inputmode="numeric" maxlength="11" minlength="11" pattern="^09\d{9}$" id="phone_number" />
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
                            <button type="button" class="btn-next step-next">Next: School Data <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
                        </div>
                    </div>

                    {{-- STEP 2: SCHOOL DATA --}}
                    <div class="step-panel" data-step="2" data-title="School Data">
                        <div class="section-title">
                            <div class="section-title-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></div>
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
                                <x-input-error :messages="$errors->get('year_level')" class="mt-1" />
                            </div>
                            <div class="field-wrap hidden" id="initialInterviewCompletedWrapper">
                                <label class="field-label">Initial Interview Completion</label>
                                <select class="auth-select" name="initial_interview_completed" id="initial_interview_completed">
                                    <option value="">Select</option>
                                    <option value="yes" {{ old('initial_interview_completed')=='yes'?'selected':'' }}>Yes, completed</option>
                                    <option value="no" {{ old('initial_interview_completed')=='no'?'selected':'' }}>No, not yet</option>
                                </select>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Course *</label>
                                <input class="auth-input" type="text" name="course" value="{{ old('course') }}" required placeholder="e.g. BS Computer Science" />
                                <x-input-error :messages="$errors->get('course')" class="mt-1" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">College *</label>
                                <select class="auth-select" name="college_id" required>
                                    <option value="">Select College</option>
                                    @foreach($colleges as $college)
                                        <option value="{{ $college->id }}" {{ old('college_id')==$college->id?'selected':'' }}>{{ $college->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('college_id')" class="mt-1" />
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
                                <x-input-error :messages="$errors->get('student_status')" class="mt-1" />
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
                            <button type="button" class="btn-back step-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg> Back</button>
                            <button type="button" class="btn-next step-next">Next: Personal Data <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
                        </div>
                    </div>

                    {{-- STEP 3: PERSONAL DATA --}}
                    <div class="step-panel" data-step="3" data-title="Personal Data">
                        <div class="section-title">
                            <div class="section-title-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
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
                            <div class="field-wrap span2">
                                <label class="field-label">Leisure / Recreational Activities (comma-separated)</label>
                                <textarea class="auth-textarea" name="leisure_activities" placeholder="e.g. reading, gaming, hiking">{{ old('leisure_activities') }}</textarea>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Serious Medical Condition</label>
                                <input class="auth-input" type="text" name="serious_medical_condition" value="{{ old('serious_medical_condition') }}" placeholder="Specify or leave blank if none" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Physical Disability</label>
                                <input class="auth-input" type="text" name="physical_disability" value="{{ old('physical_disability') }}" placeholder="Specify or leave blank if none" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Sex Identity</label>
                                <select class="auth-select" name="sex_identity">
                                    <option value="">Select</option>
                                    @foreach(['male/man'=>'Male/Man','female/woman'=>'Female/Woman','transsex male/man'=>'Transsex Male/Man','transsex female/woman'=>'Transsex Female/Woman','sex variant/nonconforming'=>'Sex Variant/Nonconforming','not listed'=>'Not Listed','prefer not to say'=>'Prefer not to say'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('sex_identity')==$v?'selected':'' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Romantic/Emotional/Sexual Attraction</label>
                                <select class="auth-select" name="romantic_attraction">
                                    <option value="">Select</option>
                                    @foreach(['my same sex'=>'My same sex','opposite sex'=>'Opposite sex','both men and women'=>'Both men and women','all sexes'=>'All sexes','neither sex'=>'Neither sex','prefer not to answer'=>'Prefer not to answer'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('romantic_attraction')==$v?'selected':'' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="nav-btns">
                            <button type="button" class="btn-back step-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg> Back</button>
                            <button type="button" class="btn-next step-next">Next: Family Data <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
                        </div>
                    </div>

                    {{-- STEP 4: FAMILY DATA --}}
                    <div class="step-panel" data-step="4" data-title="Family Data">
                        <div class="section-title">
                            <div class="section-title-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
                            Family Data
                        </div>
                        <div class="field-grid">
                            <div class="field-wrap">
                                <label class="field-label">Father's Name *</label>
                                <input class="auth-input" type="text" name="father_name" value="{{ old('father_name') }}" required placeholder="Full name" />
                                <label class="check-item" style="margin-top:8px;"><input type="checkbox" name="father_deceased" value="1" {{ old('father_deceased')?'checked':'' }}> Deceased</label>
                                <x-input-error :messages="$errors->get('father_name')" class="mt-1" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Father's Occupation *</label>
                                <input class="auth-input" type="text" name="father_occupation" value="{{ old('father_occupation') }}" required placeholder="Occupation" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Father's Phone Number *</label>
                                <input class="auth-input" type="text" name="father_phone_number" value="{{ old('father_phone_number') }}" required placeholder="09XXXXXXXXX" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Mother's Name *</label>
                                <input class="auth-input" type="text" name="mother_name" value="{{ old('mother_name') }}" required placeholder="Full name" />
                                <label class="check-item" style="margin-top:8px;"><input type="checkbox" name="mother_deceased" value="1" {{ old('mother_deceased')?'checked':'' }}> Deceased</label>
                                <x-input-error :messages="$errors->get('mother_name')" class="mt-1" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Mother's Occupation *</label>
                                <input class="auth-input" type="text" name="mother_occupation" value="{{ old('mother_occupation') }}" required placeholder="Occupation" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Mother's Phone Number *</label>
                                <input class="auth-input" type="text" name="mother_phone_number" value="{{ old('mother_phone_number') }}" required placeholder="09XXXXXXXXX" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Parents' Marital Status *</label>
                                <select class="auth-select" name="parents_marital_status" required>
                                    <option value="">Select</option>
                                    @foreach(['married'=>'Married','not legally married'=>'Not Legally Married','separated'=>'Separated','both parents remarried'=>'Both Parents Remarried','one parent remarried'=>'One Parent Remarried'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('parents_marital_status')==$v?'selected':'' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Family Monthly Income *</label>
                                <select class="auth-select" name="family_monthly_income" required>
                                    <option value="">Select</option>
                                    @foreach(['below 3k'=>'Below ₱3,000','3001-5000'=>'₱3,001–₱5,000','5001-8000'=>'₱5,001–₱8,000','8001-10000'=>'₱8,001–₱10,000','10001-15000'=>'₱10,001–₱15,000','15001-20000'=>'₱15,001–₱20,000','20001 above'=>'₱20,001 and above'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('family_monthly_income')==$v?'selected':'' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Guardian Name</label>
                                <input class="auth-input" type="text" name="guardian_name" value="{{ old('guardian_name') }}" placeholder="If not staying with parents" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Guardian Occupation</label>
                                <input class="auth-input" type="text" name="guardian_occupation" value="{{ old('guardian_occupation') }}" placeholder="Occupation" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Guardian Phone Number</label>
                                <input class="auth-input" type="text" name="guardian_phone_number" value="{{ old('guardian_phone_number') }}" placeholder="09XXXXXXXXX" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Relationship with Guardian</label>
                                <input class="auth-input" type="text" name="guardian_relationship" value="{{ old('guardian_relationship') }}" placeholder="e.g. Uncle, Aunt" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Ordinal Position in Family *</label>
                                <select class="auth-select" name="ordinal_position" required>
                                    <option value="">Select</option>
                                    @foreach(['only child'=>'Only Child','eldest'=>'Eldest','middle'=>'Middle','youngest'=>'Youngest'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('ordinal_position')==$v?'selected':'' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Number of Siblings *</label>
                                <input class="auth-input" type="number" name="number_of_siblings" value="{{ old('number_of_siblings', 0) }}" min="0" required />
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">Describe Your Home Environment *</label>
                                <textarea class="auth-textarea" name="home_environment_description" required placeholder="Describe your home environment">{{ old('home_environment_description') }}</textarea>
                            </div>
                        </div>
                        <div class="nav-btns">
                            <button type="button" class="btn-back step-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg> Back</button>
                            <button type="button" class="btn-next step-next">Next: Academic & Career <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
                        </div>
                    </div>

                    {{-- STEP 5: ACADEMIC & CAREER --}}
                    <div class="step-panel" data-step="5" data-title="Academic & Career">
                        <div class="section-title">
                            <div class="section-title-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg></div>
                            Academic and Career Data
                        </div>
                        <div class="field-grid">
                            <div class="field-wrap">
                                <label class="field-label">SHS General Average / GPA</label>
                                <input class="auth-input" type="number" step="0.01" name="shs_gpa" value="{{ old('shs_gpa') }}" placeholder="e.g. 92.50" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Scholar?</label>
                                <div style="display:flex;align-items:center;gap:10px;margin-top:14px;">
                                    <label class="check-item"><input type="checkbox" name="is_scholar" value="1" id="is_scholar" {{ old('is_scholar')?'checked':'' }}> Yes, I am a scholar</label>
                                </div>
                            </div>
                            <div class="field-wrap" id="scholarship_type_wrap" style="{{ old('is_scholar')?'':'display:none' }}">
                                <label class="field-label">Scholarship Type</label>
                                <input class="auth-input" type="text" name="scholarship_type" value="{{ old('scholarship_type') }}" placeholder="e.g. DOST, CHED" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">School Last Attended</label>
                                <input class="auth-input" type="text" name="school_last_attended" value="{{ old('school_last_attended') }}" placeholder="School name" />
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">School Address</label>
                                <textarea class="auth-textarea" name="school_address" placeholder="School address">{{ old('school_address') }}</textarea>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">SHS Track</label>
                                <select class="auth-select" name="shs_track">
                                    <option value="">Select Track</option>
                                    @foreach(['academic'=>'Academic','arts/design'=>'Arts/Design','tech-voc'=>'Tech-Voc','sports'=>'Sports'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('shs_track')==$v?'selected':'' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">SHS Strand</label>
                                <select class="auth-select" name="shs_strand">
                                    <option value="">Select Strand</option>
                                    @foreach(['GA'=>'GA','STEM'=>'STEM','HUMMS'=>'HUMMS','ABM'=>'ABM'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('shs_strand')==$v?'selected':'' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">Awards / Honors Received (comma-separated)</label>
                                <textarea class="auth-textarea" name="awards_honors" placeholder="e.g. Valedictorian, Best in Math, None">{{ old('awards_honors') }}</textarea>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">Student Organizations (comma-separated)</label>
                                <textarea class="auth-textarea" name="student_organizations" placeholder="e.g. Math Club, Debate Team">{{ old('student_organizations') }}</textarea>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">Co-curricular Activities (comma-separated)</label>
                                <textarea class="auth-textarea" name="co_curricular_activities" placeholder="e.g. Sports, Clubs, Volunteering">{{ old('co_curricular_activities') }}</textarea>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">1st Career / Course Option</label>
                                <input class="auth-input" type="text" name="career_option_1" value="{{ old('career_option_1') }}" placeholder="e.g. Software Engineer" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">2nd Career / Course Option</label>
                                <input class="auth-input" type="text" name="career_option_2" value="{{ old('career_option_2') }}" placeholder="e.g. Data Analyst" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">3rd Career / Course Option</label>
                                <input class="auth-input" type="text" name="career_option_3" value="{{ old('career_option_3') }}" placeholder="e.g. Teacher" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Whose Choice is Your Course?</label>
                                <select class="auth-select" name="course_choice_by" id="course_choice_by">
                                    <option value="">Select</option>
                                    @foreach(['own choice'=>'Own Choice','parents choice'=>"Parents' Choice",'relative choice'=>"Relative's Choice",'sibling choice'=>"Sibling's Choice",'according to MSU-SASE score/slot'=>'According to MSU-SASE Score/Slot','others'=>'Others'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('course_choice_by')==$v?'selected':'' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-wrap {{ old('course_choice_by')!='others'?'hidden':'' }}" id="course_choice_other_container">
                                <label class="field-label">Specify Course Choice</label>
                                <input class="auth-input" type="text" name="course_choice_other" value="{{ old('course_choice_other') }}" placeholder="Please specify" />
                                <x-input-error :messages="$errors->get('course_choice_other')" class="mt-1" />
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">Reason for Choosing the Course</label>
                                <textarea class="auth-textarea" name="course_choice_reason" placeholder="Explain your reason">{{ old('course_choice_reason') }}</textarea>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">What Makes You Choose MSU-IIT?</label>
                                <div class="check-group">
                                    @foreach(['Quality Education','Affordable Tuition Fees','Scholarships','Proximity','Only school offering my course','Prestigious Institution','Others'] as $r)
                                        <label class="check-item"><input type="checkbox" name="msu_choice_reasons[]" value="{{ $r }}" {{ in_array($r, old('msu_choice_reasons', []))?'checked':'' }} class="msu-reason-cb"> {{ $r }}</label>
                                    @endforeach
                                </div>
                                <div class="field-wrap {{ !in_array('Others', old('msu_choice_reasons', []))?'hidden':'' }}" id="msu_choice_other_container" style="margin-top:10px;">
                                    <input class="auth-input" type="text" name="msu_choice_other" value="{{ old('msu_choice_other') }}" placeholder="Please specify" />
                                    <x-input-error :messages="$errors->get('msu_choice_other')" class="mt-1" />
                                </div>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">Future Career Plans</label>
                                <textarea class="auth-textarea" name="future_career_plans" placeholder="What career do you see yourself pursuing after college?">{{ old('future_career_plans') }}</textarea>
                            </div>
                        </div>
                        <div class="nav-btns">
                            <button type="button" class="btn-back step-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg> Back</button>
                            <button type="button" class="btn-next step-next">Next: Learning Resources <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
                        </div>
                    </div>

                    {{-- STEP 6: LEARNING RESOURCES --}}
                    <div class="step-panel" data-step="6" data-title="Learning Resources">
                        <div class="section-title">
                            <div class="section-title-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
                            Distance Learning Resources
                        </div>
                        <div class="field-grid">
                            <div class="field-wrap">
                                <label class="field-label">Internet Access and Resources</label>
                                <select class="auth-select" name="internet_access">
                                    <option value="">Select</option>
                                    @foreach(['no internet access'=>'No Internet Access','limited internet access'=>'Limited Internet Access','full internet access'=>'Full Internet Access'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('internet_access')==$v?'selected':'' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Distance Learning Readiness</label>
                                <select class="auth-select" name="distance_learning_readiness">
                                    <option value="">Select</option>
                                    @foreach(['fully ready'=>'Fully Ready','ready'=>'Ready','a little ready'=>'A Little Ready','not ready'=>'Not Ready'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('distance_learning_readiness')==$v?'selected':'' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">Technology Gadgets</label>
                                <div class="check-group">
                                    @foreach(['None','Mobile phone','Smartphone','Tablet/iPad','Laptop/Notebook','PC/Desktop','Other'] as $g)
                                        <label class="check-item"><input type="checkbox" name="technology_gadgets[]" value="{{ $g }}" {{ in_array($g, old('technology_gadgets', []))?'checked':'' }} class="gadget-cb"> {{ $g }}</label>
                                    @endforeach
                                </div>
                                <div class="field-wrap {{ !in_array('Other', old('technology_gadgets', []))?'hidden':'' }}" id="technology_gadgets_other_container" style="margin-top:10px;">
                                    <input class="auth-input" type="text" name="technology_gadgets_other" value="{{ old('technology_gadgets_other') }}" placeholder="Please specify" />
                                    <x-input-error :messages="$errors->get('technology_gadgets_other')" class="mt-1" />
                                </div>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">Means of Internet Connectivity</label>
                                <div class="check-group">
                                    @foreach(['Home Internet',"Relative's Internet","Neighbor's Internet",'Mobile Data','Piso Net','Internet Café','No Internet','Others'] as $c)
                                        <label class="check-item"><input type="checkbox" name="internet_connectivity[]" value="{{ $c }}" {{ in_array($c, old('internet_connectivity', []))?'checked':'' }} class="connectivity-cb"> {{ $c }}</label>
                                    @endforeach
                                </div>
                                <div class="field-wrap {{ !in_array('Others', old('internet_connectivity', []))?'hidden':'' }}" id="internet_connectivity_other_container" style="margin-top:10px;">
                                    <input class="auth-input" type="text" name="internet_connectivity_other" value="{{ old('internet_connectivity_other') }}" placeholder="Please specify" />
                                    <x-input-error :messages="$errors->get('internet_connectivity_other')" class="mt-1" />
                                </div>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">Describe Your Learning Space</label>
                                <textarea class="auth-textarea" name="learning_space_description" placeholder="Describe your study area and environment">{{ old('learning_space_description') }}</textarea>
                            </div>
                        </div>
                        <div class="nav-btns">
                            <button type="button" class="btn-back step-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg> Back</button>
                            <button type="button" class="btn-next step-next">Next: Psychosocial <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
                        </div>
                    </div>

                    {{-- STEP 7: PSYCHOSOCIAL --}}
                    <div class="step-panel" data-step="7" data-title="Psychosocial">
                        <div class="section-title">
                            <div class="section-title-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
                            Psychosocial Well-being
                        </div>
                        <div class="field-grid">
                            <div class="field-wrap span2">
                                <label class="field-label">Personality Characteristics (comma-separated)</label>
                                <textarea class="auth-textarea" name="personality_characteristics" placeholder="e.g. organized, outgoing, analytical, creative">{{ old('personality_characteristics') }}</textarea>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">How Do You Deal with a Bad Day? (comma-separated)</label>
                                <textarea class="auth-textarea" name="coping_mechanisms" placeholder="e.g. talk to friends, listen to music, exercise, meditate">{{ old('coping_mechanisms') }}</textarea>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">How Do You Perceive Your Mental Health at Present?</label>
                                <input class="auth-input" type="text" name="mental_health_perception" value="{{ old('mental_health_perception') }}" placeholder="Describe your mental health perception" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Have You Experienced Counseling Before?</label>
                                <div class="radio-group">
                                    <label class="radio-item"><input type="radio" name="had_counseling_before" value="1" {{ old('had_counseling_before')=='1'?'checked':'' }}> Yes</label>
                                    <label class="radio-item"><input type="radio" name="had_counseling_before" value="0" {{ old('had_counseling_before')=='0'||!old('had_counseling_before')?'checked':'' }}> No</label>
                                </div>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Seeking Help from Psychologist/Psychiatrist?</label>
                                <div class="radio-group">
                                    <label class="radio-item"><input type="radio" name="sought_psychologist_help" value="1" {{ old('sought_psychologist_help')=='1'?'checked':'' }}> Yes</label>
                                    <label class="radio-item"><input type="radio" name="sought_psychologist_help" value="0" {{ old('sought_psychologist_help')=='0'||!old('sought_psychologist_help')?'checked':'' }}> No</label>
                                </div>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">To Whom Do You Share Your Problems With?</label>
                                <div class="check-group">
                                    @foreach(['Mother','Father','Brother/Sister','Friends','Counselor','Others'] as $t)
                                        <label class="check-item"><input type="checkbox" name="problem_sharing_targets[]" value="{{ $t }}" {{ in_array($t, old('problem_sharing_targets', []))?'checked':'' }} class="problem-cb"> {{ $t }}</label>
                                    @endforeach
                                </div>
                                <div class="field-wrap {{ !in_array('Others', old('problem_sharing_targets', []))?'hidden':'' }}" id="problem_sharing_other_container" style="margin-top:10px;">
                                    <input class="auth-input" type="text" name="problem_sharing_other" value="{{ old('problem_sharing_other') }}" placeholder="Please specify" />
                                    <x-input-error :messages="$errors->get('problem_sharing_other')" class="mt-1" />
                                </div>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Need Immediate Counseling?</label>
                                <div class="radio-group">
                                    <label class="radio-item"><input type="radio" name="needs_immediate_counseling" value="1" {{ old('needs_immediate_counseling')=='1'?'checked':'' }}> Yes</label>
                                    <label class="radio-item"><input type="radio" name="needs_immediate_counseling" value="0" {{ old('needs_immediate_counseling')=='0'||!old('needs_immediate_counseling')?'checked':'' }}> No</label>
                                </div>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">What Concerns Would You Like to Discuss with a Counselor?</label>
                                <textarea class="auth-textarea" name="future_counseling_concerns" placeholder="Describe your concerns">{{ old('future_counseling_concerns') }}</textarea>
                            </div>
                        </div>
                        <div class="nav-btns">
                            <button type="button" class="btn-back step-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg> Back</button>
                            <button type="button" class="btn-next step-next">Next: Needs Assessment <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
                        </div>
                    </div>

                    {{-- STEP 8: NEEDS ASSESSMENT --}}
                    <div class="step-panel" data-step="8" data-title="Needs Assessment">
                        <div class="section-title">
                            <div class="section-title-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg></div>
                            Needs Assessment
                        </div>
                        <div class="field-grid">
                            <div class="field-wrap span2">
                                <label class="field-label">I Have the Need to Improve the Following:</label>
                                <div class="check-group">
                                    @foreach(['Study habits','Note-taking skills','Time-management skills','Career decision/choices','Math skills','Reading comprehension','Memory skills','Test-taking skills','Grade point average','Reading speed','Others'] as $n)
                                        <label class="check-item"><input type="checkbox" name="improvement_needs[]" value="{{ $n }}" {{ in_array($n, old('improvement_needs', []))?'checked':'' }} class="improvement-cb"> {{ $n }}</label>
                                    @endforeach
                                </div>
                                <div class="field-wrap {{ !in_array('Others', old('improvement_needs', []))?'hidden':'' }}" id="improvement_needs_other_container" style="margin-top:10px;">
                                    <input class="auth-input" type="text" name="improvement_needs_other" value="{{ old('improvement_needs_other') }}" placeholder="Please specify" />
                                    <x-input-error :messages="$errors->get('improvement_needs_other')" class="mt-1" />
                                </div>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">I Need Assistance in Terms of:</label>
                                <div class="check-group">
                                    @foreach(['Personal budget','Grants/scholarships','Loans','Others'] as $f)
                                        <label class="check-item"><input type="checkbox" name="financial_assistance_needs[]" value="{{ $f }}" {{ in_array($f, old('financial_assistance_needs', []))?'checked':'' }} class="financial-cb"> {{ $f }}</label>
                                    @endforeach
                                </div>
                                <div class="field-wrap {{ !in_array('Others', old('financial_assistance_needs', []))?'hidden':'' }}" id="financial_assistance_needs_other_container" style="margin-top:10px;">
                                    <input class="auth-input" type="text" name="financial_assistance_needs_other" value="{{ old('financial_assistance_needs_other') }}" placeholder="Please specify" />
                                    <x-input-error :messages="$errors->get('financial_assistance_needs_other')" class="mt-1" />
                                </div>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">I Need Assistance in Terms of Personal-Social:</label>
                                <div class="check-group">
                                    @foreach(['Stress management','Substance abuse','Dealing with relationships (Boy/Girl)','Anxiety','Handling conflicts/anger','Coping with peer pressure','Student-teacher conflict','Coping with physical disability','Student-teacher/school personnel relationship','Depression/Sadness','Motivation','Self-image (how you feel about yourself)','Grief/loss due to parental separation','Grief/loss due to death','Physical/psychological abuse','Bullying','Cyber-bullying','Others'] as $p)
                                        <label class="check-item"><input type="checkbox" name="personal_social_needs[]" value="{{ $p }}" {{ in_array($p, old('personal_social_needs', []))?'checked':'' }} class="personal-social-cb"> {{ $p }}</label>
                                    @endforeach
                                </div>
                                <div class="field-wrap {{ !in_array('Others', old('personal_social_needs', []))?'hidden':'' }}" id="personal_social_needs_other_container" style="margin-top:10px;">
                                    <input class="auth-input" type="text" name="personal_social_needs_other" value="{{ old('personal_social_needs_other') }}" placeholder="Please specify" />
                                    <x-input-error :messages="$errors->get('personal_social_needs_other')" class="mt-1" />
                                </div>
                            </div>

                            <div class="field-wrap span2">
                                <label class="field-label">When Upset or Pushed to the Limit, How Did You Respond?</label>
                                <div class="check-group">
                                    @foreach(['Tried to be funny and make light of it all','Talked to a teacher or counselor in school','Ate food','Tried to stay away from home','Drank beer, wine, liquor','Used drugs not prescribed by doctor','Listened to music','Watched movies or TV shows','Smoked','Tried to solve my problem','Read books, novels, etc.','Worked hard on school work/projects','Attempted to end my life','Got more involved in school activities','Tried to make my own decision','Talked things out with parents','Cried','Tried to improve myself','Strolled around on a car/jeepney ride','Tried to think of the good things in life','Prayed','Thought it would be better dead','Talked to a minister/priest/pastor','Told myself the problem is not important','Blamed others for what went wrong','Played video games','Surfed the internet','Hurt myself','Talked to a friend','Daydreamed about how I would like things to be','Got professional counseling','Went to church','Slept','Got angry','Kept my silence','Others'] as $s)
                                        <label class="check-item"><input type="checkbox" name="stress_responses[]" value="{{ $s }}" {{ in_array($s, old('stress_responses', []))?'checked':'' }} class="stress-cb"> {{ $s }}</label>
                                    @endforeach
                                </div>
                                <div class="field-wrap {{ !in_array('Others', old('stress_responses', []))?'hidden':'' }}" id="stress_responses_other_container" style="margin-top:10px;">
                                    <input class="auth-input" type="text" name="stress_responses_other" value="{{ old('stress_responses_other') }}" placeholder="Please specify" />
                                    <x-input-error :messages="$errors->get('stress_responses_other')" class="mt-1" />
                                </div>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">I Can Easily Discuss My Problems With My:</label>
                                <div class="radio-group" style="flex-wrap:wrap;">
                                    @foreach(['guidance counselor'=>'Guidance counselor in school','parents'=>'Parents','teachers'=>'Teacher(s)','brothers/sisters'=>'Brothers/Sisters','friends/relatives'=>'Friends/Relatives','nobody'=>'Nobody','others'=>'Others'] as $v=>$l)
                                        <label class="radio-item"><input type="radio" name="easy_discussion_target" value="{{ $v }}" {{ old('easy_discussion_target')==$v?'checked':'' }} class="easy-discussion-radio"> {{ $l }}</label>
                                    @endforeach
                                </div>
                                <div class="field-wrap {{ old('easy_discussion_target')!='others'?'hidden':'' }}" id="easy_discussion_other_container" style="margin-top:10px;">
                                    <input class="auth-input" type="text" name="easy_discussion_other" value="{{ old('easy_discussion_other') }}" placeholder="Please specify" />
                                    <x-input-error :messages="$errors->get('easy_discussion_other')" class="mt-1" />
                                </div>
                            </div>
                            <div class="field-wrap span2">
                                <label class="field-label">How Often Did You Experience or Perceive the Following?</label>
                                <div class="table-wrap">
                                    <table class="freq-table">
                                        <thead>
                                            <tr>
                                                <th>Statement</th>
                                                <th>Always</th>
                                                <th>Oftentimes</th>
                                                <th>Sometimes</th>
                                                <th>Never</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $counselingStatements = [
                                                'I willfully came for counseling when I had a problem.',
                                                'I experienced counseling upon referral by teachers, friends, parents, etc.',
                                                'I know that help is available at the Guidance and Counseling Center of MSU-IIT.',
                                                'I am afraid to go to the Guidance and Counseling Center of MSU-IIT.',
                                                'I am shy to ask assistance/seek counseling from my guidance counselor.',
                                            ];
                                            @endphp
                                            @foreach($counselingStatements as $idx => $stmt)
                                                <tr>
                                                    <td>{{ $stmt }}</td>
                                                    @foreach(['always','oftentimes','sometimes','never'] as $freq)
                                                        <td><input type="radio" name="counseling_perceptions[{{ $idx }}]" value="{{ $freq }}" {{ old("counseling_perceptions.{$idx}")==$freq?'checked':'' }}></td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="nav-btns">
                            <button type="button" class="btn-back step-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg> Back</button>
                            <button type="button" class="btn-next step-next">Next: Account Security <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
                        </div>
                    </div>

                    {{-- STEP 9: ACCOUNT SECURITY --}}
                    <div class="step-panel" data-step="9" data-title="Account Security">
                        <div class="section-title">
                            <div class="section-title-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg></div>
                            Account Security
                        </div>
                        <div class="field-grid">
                            <div class="field-wrap">
                                <label class="field-label">Password *</label>
                                <input class="auth-input" type="password" name="password" required autocomplete="new-password" placeholder="Create a strong password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Confirm Password *</label>
                                <input class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password" />
                            </div>
                        </div>
                        <input type="hidden" name="role" value="student">
                        <div class="nav-btns">
                            <button type="button" class="btn-back step-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg> Back</button>
                            <button type="submit" class="btn-submit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                Register
                            </button>
                        </div>
                    </div>

                </form>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Step navigation
    const panels = Array.from(document.querySelectorAll('.step-panel'));
    let current = 0;

    function buildPills() {
        const container = document.getElementById('stepPills');
        const bar = document.getElementById('progressBar');
        if (!container) return;
        container.innerHTML = '';
        panels.forEach((p, i) => {
            const pill = document.createElement('span');
            pill.className = 'step-pill' + (i === current ? ' active' : i < current ? ' done' : '');
            pill.textContent = (i + 1) + '. ' + (p.dataset.title || 'Step ' + (i + 1));
            container.appendChild(pill);
        });
        if (bar) bar.style.width = panels.length > 1 ? ((current / (panels.length - 1)) * 100) + '%' : '0%';
    }

    function goTo(idx) {
        if (!panels[idx]) return;
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

    // Birthdate -> Age
    const bdInput = document.getElementById('birthdate');
    const ageInput = document.getElementById('age');
    if (bdInput && ageInput) {
        bdInput.addEventListener('change', function () {
            const bd = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - bd.getFullYear();
            const m = today.getMonth() - bd.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < bd.getDate())) age--;
            ageInput.value = isNaN(age) ? '' : age;
        });
    }

    // Phone number validation
    const phoneInput = document.getElementById('phone_number');
    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            const cleaned = this.value.replace(/\D+/g, '').slice(0, 11);
            if (this.value !== cleaned) this.value = cleaned;
            if (cleaned.length === 0) this.setCustomValidity('');
            else if (!/^09\d{9}$/.test(cleaned)) this.setCustomValidity('Phone number must be 11 digits and start with 09');
            else this.setCustomValidity('');
        });
    }

    // Civil status other
    const csSelect = document.getElementById('civil_status');
    const csOther = document.getElementById('civil_status_other_container');
    if (csSelect && csOther) {
        csSelect.addEventListener('change', function () { csOther.classList.toggle('hidden', this.value !== 'others'); });
    }

    // Year level -> initial interview
    const ylSelect = document.getElementById('year_level');
    const iiWrapper = document.getElementById('initialInterviewCompletedWrapper');
    const iiSelect = document.getElementById('initial_interview_completed');
    if (ylSelect && iiWrapper) {
        ylSelect.addEventListener('change', function () {
            const is12 = ['1st Year', '2nd Year'].includes(this.value);
            const is345 = ['3rd Year', '4th Year', '5th Year'].includes(this.value);
            iiWrapper.classList.toggle('hidden', !is12);
            if (iiSelect) {
                iiSelect.required = is12;
                if (is345) iiSelect.value = 'yes';
                else if (!is12) iiSelect.value = '';
            }
        });
    }

    // Scholar field toggle
    const scholarCb = document.getElementById('is_scholar');
    const scholarWrap = document.getElementById('scholarship_type_wrap');
    if (scholarCb && scholarWrap) {
        scholarCb.addEventListener('change', function () { scholarWrap.style.display = this.checked ? '' : 'none'; });
    }

    // Course choice other
    const ccSelect = document.getElementById('course_choice_by');
    const ccOther = document.getElementById('course_choice_other_container');
    if (ccSelect && ccOther) {
        ccSelect.addEventListener('change', function () { ccOther.classList.toggle('hidden', this.value !== 'others'); });
    }

    // Easy discussion other
    document.querySelectorAll('.easy-discussion-radio').forEach(r => {
        r.addEventListener('change', function () {
            const c = document.getElementById('easy_discussion_other_container');
            if (c) c.classList.toggle('hidden', this.value !== 'others');
        });
    });

    // Profile picture label
    const fileInput = document.getElementById('profile_picture');
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            const label = this.closest('.file-upload-wrap').querySelector('.file-upload-text');
            if (label && this.files[0]) label.textContent = '✓ ' + this.files[0].name;
        });
    }

    // Generic "Others" checkbox toggle helper
    function setupOthersCb(cbClass, containerId, otherValue) {
        document.querySelectorAll('.' + cbClass).forEach(cb => {
            cb.addEventListener('change', function () {
                const anyOther = Array.from(document.querySelectorAll('.' + cbClass)).some(c => c.value === otherValue && c.checked);
                const container = document.getElementById(containerId);
                if (container) container.classList.toggle('hidden', !anyOther);
            });
        });
    }

    setupOthersCb('msu-reason-cb', 'msu_choice_other_container', 'Others');
    setupOthersCb('gadget-cb', 'technology_gadgets_other_container', 'Other');
    setupOthersCb('connectivity-cb', 'internet_connectivity_other_container', 'Others');
    setupOthersCb('problem-cb', 'problem_sharing_other_container', 'Others');
    setupOthersCb('improvement-cb', 'improvement_needs_other_container', 'Others');
    setupOthersCb('financial-cb', 'financial_assistance_needs_other_container', 'Others');
    setupOthersCb('personal-social-cb', 'personal_social_needs_other_container', 'Others');
    setupOthersCb('stress-cb', 'stress_responses_other_container', 'Others');
});
</script>
</x-guest-layout>
