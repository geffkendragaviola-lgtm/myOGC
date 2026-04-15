@extends(Auth::check() && Auth::user()->role === 'counselor' ? 'layouts.app' : 'layouts.student')

@section('title', 'Student Details')

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
        --text-primary: #1a1512;
        --text-secondary: #5a4f47;
        --text-muted: #7a6e66;
        --text-on-light: #2c2420;
        --text-on-dark: #fff9e7;
    }

    /* Minimal Base */
    .student-shell {
        background: var(--bg-warm);
        min-height: 100vh;
        font-family: system-ui, -apple-system, sans-serif;
        color: var(--text-primary);
    }

    .card {
        background: white;
        border: 1px solid var(--border-soft);
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .card-header {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-soft);
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-header i { color: var(--maroon-700); }

    .card-body { padding: 1rem; }

    /* Header */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        margin-bottom: 1rem;
        background: white;
        border: 1px solid var(--border-soft);
        border-radius: 0.5rem;
        border-left: 3px solid var(--maroon-700);
    }

    .page-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .page-subtitle {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin: 0.25rem 0 0;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 0.85rem;
        border-radius: 0.4rem;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        border: 1px solid var(--border-soft);
        background: white;
        color: var(--text-primary);
    }

    .btn:hover { background: rgba(212,175,55,0.15); border-color: var(--maroon-700); }

    .btn-primary {
        background: var(--maroon-700);
        color: white;
        border-color: var(--maroon-700);
    }
    .btn-primary:hover { background: var(--maroon-800); }

    /* Profile Card */
    .profile-card { text-align: center; }
    .profile-avatar {
        width: 5rem; height: 5rem;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--border-soft);
        margin: 0 auto 0.75rem;
        background: var(--bg-warm);
    }
    .profile-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.25rem;
    }
    .profile-id {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin: 0 0 0.5rem;
    }
    .profile-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        justify-content: center;
        margin-bottom: 0.75rem;
    }
    .chip {
        padding: 0.2rem 0.5rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        background: rgba(212,175,55,0.2);
        color: var(--maroon-800);
        border: 1px solid rgba(212,175,55,0.4);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }
    .stat-item {
        text-align: center;
        padding: 0.6rem;
        background: var(--bg-warm);
        border-radius: 0.4rem;
        border: 1px solid var(--border-soft);
    }
    .stat-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .stat-label {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    /* Contact List */
    .contact-list { font-size: 0.8rem; }
    .contact-list strong { color: var(--text-primary); font-weight: 600; }
    .contact-list a { 
        color: var(--maroon-700); 
        text-decoration: none;
        font-weight: 500;
    }
    .contact-list a:hover { 
        color: var(--maroon-900); 
        text-decoration: underline; 
    }

    /* Tabs - Minimal */
    .tabs-nav {
        display: flex;
        gap: 0.25rem;
        overflow-x: auto;
        padding: 0.25rem 0;
        border-bottom: 1px solid var(--border-soft);
        margin-bottom: 1rem;
    }
    .tab-btn {
        padding: 0.5rem 0.85rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.15s ease;
    }
    .tab-btn:hover { 
        color: var(--maroon-700); 
        background: rgba(212,175,55,0.1);
    }
    .tab-btn.active {
        color: var(--maroon-800);
        border-bottom-color: var(--gold-500);
        background: rgba(212,175,55,0.15);
        font-weight: 700;
    }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }

    /* Tables */
    .info-table {
        width: 100%;
        font-size: 0.8rem;
        border-collapse: collapse;
    }
    .info-table th {
        text-align: left;
        padding: 0.4rem 0;
        color: var(--text-secondary);
        font-weight: 600;
        width: 40%;
    }
    .info-table td {
        padding: 0.4rem 0;
        color: var(--text-primary);
        font-weight: 500;
    }
    .info-table tr { border-bottom: 1px dashed var(--border-soft); }
    .info-table tr:last-child { border-bottom: none; }

    /* Badges - Enhanced Visibility */
    .badge-cloud { display: flex; flex-wrap: wrap; gap: 0.3rem; }
    .badge {
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .badge.bg-primary { 
        background: rgba(212,175,55,0.25); 
        color: var(--maroon-900); 
        border: 1px solid rgba(212,175,55,0.5); 
    }
    .badge.bg-success { 
        background: rgba(16,185,129,0.2); 
        color: #042f24; 
        border: 1px solid rgba(16,185,129,0.4); 
    }
    .badge.bg-warning { 
        background: rgba(245,158,11,0.25); 
        color: #7c3d0a; 
        border: 1px solid rgba(245,158,11,0.5); 
    }
    .badge.bg-danger { 
        background: rgba(239,68,68,0.2); 
        color: #7f1d1d; 
        border: 1px solid rgba(239,68,68,0.4); 
    }
    .badge.bg-info { 
        background: rgba(59,130,246,0.2); 
        color: #1e3a5f; 
        border: 1px solid rgba(59,130,246,0.4); 
    }
    .badge.bg-secondary { 
        background: rgba(156,163,175,0.25); 
        color: #1f2937; 
        border: 1px solid rgba(156,163,175,0.5); 
    }

    /* Alerts - High Contrast Text */
    .alert {
        padding: 0.75rem 1rem;
        border-radius: 0.4rem;
        font-size: 0.8rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        font-weight: 500;
    }
    .alert-warning {
        background: rgba(254,243,199,0.95);
        border: 1px solid rgba(217,119,6,0.4);
        color: #7c3d0a;
    }
    .alert-warning strong { color: #92400e; }
    .alert-danger {
        background: rgba(254,242,242,0.95);
        border: 1px solid rgba(185,28,28,0.4);
        color: #7f1d1d;
    }
    .alert-danger strong { color: #991b1b; }
    .alert i { margin-top: 0.1rem; color: inherit; }

    /* Text Cards */
    .text-card {
        background: var(--bg-warm);
        border: 1px solid var(--border-soft);
        border-radius: 0.4rem;
        padding: 0.75rem;
        font-size: 0.8rem;
        color: var(--text-primary);
        line-height: 1.5;
        font-weight: 400;
    }

    /* Counseling Card */
    .counseling-card {
        border-left: 3px solid #d97706;
        background: rgba(254,243,199,0.95);
        border-color: rgba(217,119,6,0.4);
    }
    .counseling-card .card-header {
        background: transparent;
        border-bottom-color: rgba(217,119,6,0.3);
        color: #7c3d0a;
        font-weight: 700;
    }
    .counseling-card .card-header i { color: #d97706; }
    .counseling-card .card-body { color: var(--text-primary); }

    /* Section Titles */
    .tab-section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.75rem;
        padding-bottom: 0.4rem;
        border-bottom: 1px solid var(--border-soft);
    }

    /* Empty States */
    .empty-state {
        color: var(--text-muted);
        font-size: 0.8rem;
        text-align: center;
        padding: 1.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .info-table th, .info-table td { font-size: 0.75rem; padding: 0.3rem 0; }
        .tab-btn { padding: 0.45rem 0.75rem; font-size: 0.7rem; }
    }

    /* PRINT STYLES - High Contrast for Print */
    @media print {
        @page {
            margin: 1.5cm;
            size: A4;
        }

        body {
            background: white !important;
            color: #000 !important;
            font-size: 11pt;
            line-height: 1.4;
        }

        /* Hide UI elements */
        .btn, .tabs-nav, .alert-warning, .no-print {
            display: none !important;
        }

        /* Show all tab content for print */
        .tab-pane {
            display: block !important;
            opacity: 1 !important;
            page-break-inside: avoid;
        }

        /* Card styling for print */
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
            background: white !important;
            margin-bottom: 0.75rem !important;
            page-break-inside: avoid;
        }

        .card-header {
            border-bottom: 1px solid #000 !important;
            padding: 0.5rem !important;
            font-weight: 700 !important;
            color: #000 !important;
            font-size: 10pt !important;
        }

        .card-body { padding: 0.5rem !important; }

        /* Header for print */
        .print-header {
            display: block !important;
            border-bottom: 2px solid #000;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            page-break-after: avoid;
        }
        .print-header h1 {
            font-size: 14pt;
            font-weight: 700;
            margin: 0 0 0.25rem;
            color: #000;
        }
        .print-header .meta {
            font-size: 9pt;
            color: #333;
        }

        /* Profile section for print */
        .profile-card {
            text-align: center;
            margin-bottom: 1rem;
            page-break-after: avoid;
        }
        .profile-avatar {
            width: 4rem;
            height: 4rem;
            border: 1px solid #000;
        }
        .profile-name { font-size: 13pt; font-weight: 700; margin: 0.5rem 0 0.25rem; color: #000; }
        .profile-id { font-size: 10pt; color: #333; margin: 0; }
        .profile-chips { justify-content: center; margin: 0.5rem 0; }
        .chip {
            border: 1px solid #000;
            background: white;
            color: #000;
            font-size: 8pt;
            font-weight: 700;
        }

        /* Tables for print */
        .info-table {
            width: 100%;
            font-size: 9pt;
            border-collapse: collapse;
        }
        .info-table th {
            text-align: left;
            padding: 0.3rem 0;
            font-weight: 700;
            color: #000;
            width: 40%;
            border-bottom: 1px solid #000;
        }
        .info-table td {
            padding: 0.3rem 0;
            color: #000;
            border-bottom: 1px solid #ccc;
            font-weight: 500;
        }

        /* Badges for print - High Contrast */
        .badge {
            border: 1px solid #000;
            background: white;
            color: #000;
            font-size: 8pt;
            padding: 0.15rem 0.4rem;
            font-weight: 700;
        }

        /* Text cards for print */
        .text-card {
            border: 1px solid #000;
            background: white;
            color: #000;
            font-size: 9pt;
            padding: 0.5rem;
        }

        /* Alerts for print - High Contrast */
        .alert {
            border: 2px solid #000;
            background: white;
            color: #000;
            font-size: 9pt;
            padding: 0.5rem;
            font-weight: 600;
        }
        .alert i { display: none; }
        .alert strong { font-weight: 800; }

        /* Counseling section for print */
        .counseling-card {
            border-left: 4px solid #000;
            background: white;
            border-color: #000;
        }
        .counseling-card .card-header {
            border-bottom: 1px solid #000;
            color: #000;
            font-weight: 800;
        }

        /* Links for print */
        a { color: #000; text-decoration: none; font-weight: 600; }
        a[href]:after {
            content: " (" attr(href) ")";
            font-size: 8pt;
            color: #666;
            font-weight: 400;
        }

        /* Page breaks */
        .page-break { page-break-before: always; }

        /* Footer for print */
        .print-footer {
            display: block !important;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #000;
            border-top: 1px solid #000;
            padding: 0.5rem 0;
            font-weight: 500;
        }
    }

    /* Hidden print elements */
    .print-header, .print-footer { display: none; }

    /* Focus states for accessibility */
    .btn:focus, .tab-btn:focus {
        outline: 2px solid var(--gold-500);
        outline-offset: 2px;
    }

    /* Ensure all text has sufficient contrast */
    [class*="text-"] {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
</style>

<div class="student-shell">
    <div class="container mx-auto px-4 py-4 max-w-6xl">
        <!-- Print Header (visible only when printing) -->
        <div class="print-header">
            <h1>Student Profile Report</h1>
            <div class="meta">
                <strong>{{ $student->full_name }}</strong> | 
                ID: {{ $student->student_id }} | 
                Generated: {{ date('F j, Y g:i A') }}
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Student Profile</h1>
                <p class="page-subtitle">Complete student information and support details</p>
            </div>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Print Profile
            </button>
        </div>

        <!-- Profile Completion Alert -->
        @if($student->profile_completion['percentage'] < 100)
        <div class="alert alert-warning mb-4">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Profile Incomplete:</strong> {{ $student->profile_completion['percentage'] }}% complete.
                Missing:
                @foreach($student->profile_completion['sections'] as $section => $completed)
                    @if(!$completed) <span class="chip">{{ ucfirst($section) }}</span> @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Student Basic Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <!-- Profile Card -->
            <div class="card profile-card">
                <div class="card-body">
                    @if($student->profile_picture)
                        <img src="{{ $student->profile_picture_url }}" alt="Profile" class="profile-avatar">
                    @else
                        <div class="profile-avatar flex items-center justify-center text-2xl text-[var(--text-muted)]">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <h3 class="profile-name">{{ $student->full_name }}</h3>
                    <p class="profile-id">{{ $student->student_id }}</p>
                    <div class="profile-chips">
                        <span class="chip">{{ $student->year_level }}</span>
                        <span class="chip">{{ $student->course }}</span>
                        <span class="chip">{{ $student->student_status }}</span>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">{{ $student->registration_count }}</div>
                            <div class="stat-label">Events</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $student->appointments->count() }}</div>
                            <div class="stat-label">Appointments</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card lg:col-span-2">
                <div class="card-header"><i class="fas fa-address-card"></i> Contact Information</div>
                <div class="card-body contact-list">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                        </div>
                        <div>
                            <strong>Phone:</strong><br>
                            {{ $student->phone_number ?: 'Not provided' }}
                        </div>
                        <div>
                            <strong>Current Address (<i>in Iligan City</i>):</strong><br>
                            {{ $student->user->address ?? 'Not provided' }}
                        </div>
                        <div>
                            <strong>College:</strong><br>
                            {{ $student->college->name ?? 'Not assigned' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Details Tabs -->
        <div class="card">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="personal"><i class="fas fa-user"></i> Personal</button>
                <button class="tab-btn" data-tab="family"><i class="fas fa-home"></i> Family</button>
                <button class="tab-btn" data-tab="academic"><i class="fas fa-graduation-cap"></i> Academic</button>
                <button class="tab-btn" data-tab="learning"><i class="fas fa-laptop"></i> Learning</button>
                <button class="tab-btn" data-tab="psychosocial"><i class="fas fa-brain"></i> Psychosocial</button>
                <button class="tab-btn" data-tab="needs"><i class="fas fa-clipboard-list"></i> Needs</button>
            </div>

            <div class="card-body">
                <!-- Personal Data Tab -->
                <div class="tab-pane active" id="personal">
                    <h4 class="tab-section-title">Basic Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <table class="info-table">
                            <tr><th>First Name:</th><td>{{ $student->user->first_name ?? 'Not provided' }}</td></tr>
                            <tr><th>Middle Name:</th><td>{{ $student->user->middle_name ?: 'Not provided' }}</td></tr>
                            <tr><th>Last Name:</th><td>{{ $student->user->last_name ?? 'Not provided' }}</td></tr>
                            <tr><th>Birthdate:</th><td>{{ $student->user->birthdate ? \Carbon\Carbon::parse($student->user->birthdate)->format('M d, Y') : 'Not provided' }}</td></tr>
                            <tr><th>Age:</th><td>{{ $student->user->age ?? 'Not provided' }}</td></tr>
                            <tr><th>Sex:</th><td>{{ $student->user->sex ?: 'Not provided' }}</td></tr>
                        </table>
                        <table class="info-table">
                            <tr><th>Birthplace:</th><td>{{ $student->user->birthplace ?: 'Not provided' }}</td></tr>
                            <tr><th>Religion:</th><td>{{ $student->user->religion ?: 'Not provided' }}</td></tr>
                            <tr><th>Civil Status:</th><td>{{ $student->user->civil_status ?: 'Not provided' }}</td></tr>
                            <tr><th>Children:</th><td>{{ $student->user->number_of_children ?? 'Not provided' }}</td></tr>
                            <tr><th>Citizenship:</th><td>{{ $student->user->citizenship ?: 'Not provided' }}</td></tr>
                            <tr><th>Email:</th><td>{{ $student->email ?: 'Not provided' }}</td></tr>
                        </table>
                    </div>

                    <h4 class="tab-section-title">Registration Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <table class="info-table">
                            <tr><th>Student ID:</th><td>{{ $student->student_id ?: 'Not provided' }}</td></tr>
                            <tr><th>Year Level:</th><td>{{ $student->year_level ?: 'Not provided' }}</td></tr>
                            <tr><th>Course:</th><td>{{ $student->course ?: 'Not provided' }}</td></tr>
                            <tr><th>College:</th><td>{{ $student->college->name ?? 'Not assigned' }}</td></tr>
                        </table>
                        <table class="info-table">
                            <tr><th>MSU SASE Score:</th><td>{{ $student->msu_sase_score ?? 'Not provided' }}</td></tr>
                            <tr><th>Academic Year:</th><td>{{ $student->academic_year ?: 'Not provided' }}</td></tr>
                            <tr><th>Status:</th><td>{{ $student->student_status ?: 'Not provided' }}</td></tr>
                        </table>
                    </div>

                    @if($student->personalData)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <table class="info-table">
                                <tr><th>Nickname:</th><td>{{ $student->personalData->nickname ?: 'Not provided' }}</td></tr>
                                <tr><th>Home Address:</th><td>{{ $student->personalData->home_address ?: 'Not provided' }}</td></tr>
                                <tr><th>Stays With:</th><td>{{ $student->personalData->stays_with ?: 'Not provided' }}</td></tr>
                                <tr><th>Working Student:</th><td>{{ $student->personalData->working_student ?: 'Not provided' }}</td></tr>
                            </table>
                            <table class="info-table">
                                <tr><th>Sex Identity:</th><td>{{ $student->personalData->sex_identity ?: 'Not provided' }}</td></tr>
                                <tr><th>Romantic Attraction:</th><td>{{ $student->personalData->romantic_attraction ?: 'Not provided' }}</td></tr>
                                <tr><th>Medical Condition:</th><td>{{ $student->personalData->serious_medical_condition ?: 'None' }}</td></tr>
                                <tr><th>Physical Disability:</th><td>{{ $student->personalData->physical_disability ?: 'None' }}</td></tr>
                            </table>
                        </div>

                        @php
                            $talentsSkills = $student->personalData ? $student->personalData->talents_skills : null;
                            if (is_string($talentsSkills)) {
                                $decoded = json_decode($talentsSkills, true);
                                $talentsSkills = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $talentsSkills)));
                            }
                            $leisureActivities = $student->personalData ? $student->personalData->leisure_activities : null;
                            if (is_string($leisureActivities)) {
                                $decoded = json_decode($leisureActivities, true);
                                $leisureActivities = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $leisureActivities)));
                            }
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="tab-section-title">Talents & Skills</h5>
                                @if($talentsSkills && count($talentsSkills))
                                    <div class="badge-cloud">
                                        @foreach($talentsSkills as $skill) <span class="badge bg-primary">{{ $skill }}</span> @endforeach
                                    </div>
                                @else <span class="empty-state">Not provided</span> @endif
                            </div>
                            <div>
                                <h5 class="tab-section-title">Leisure Activities</h5>
                                @if($leisureActivities && count($leisureActivities))
                                    <div class="badge-cloud">
                                        @foreach($leisureActivities as $activity) <span class="badge bg-success">{{ $activity }}</span> @endforeach
                                    </div>
                                @else <span class="empty-state">Not provided</span> @endif
                            </div>
                        </div>
                    @else
                        <p class="empty-state">No personal data available</p>
                    @endif
                </div>

                <!-- Family Data Tab -->
                <div class="tab-pane" id="family">
                    @if($student->familyData)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <h4 class="tab-section-title">Father's Information</h4>
                                <table class="info-table">
                                    <tr><th>Name:</th><td>{{ $student->familyData->father_name ?: 'Not provided' }}</td></tr>
                                    <tr><th>Occupation:</th><td>{{ $student->familyData->father_occupation ?: 'Not provided' }}</td></tr>
                                    <tr><th>Phone:</th><td>{{ $student->familyData->father_phone_number ?: 'Not provided' }}</td></tr>
                                    <tr><th>Status:</th><td>
                                        @if($student->familyData->father_deceased) <span class="badge bg-danger">Deceased</span>
                                        @else <span class="badge bg-success">Living</span> @endif
                                    </td></tr>
                                </table>
                            </div>
                            <div>
                                <h4 class="tab-section-title">Mother's Information</h4>
                                <table class="info-table">
                                    <tr><th>Name:</th><td>{{ $student->familyData->mother_name ?: 'Not provided' }}</td></tr>
                                    <tr><th>Occupation:</th><td>{{ $student->familyData->mother_occupation ?: 'Not provided' }}</td></tr>
                                    <tr><th>Phone:</th><td>{{ $student->familyData->mother_phone_number ?: 'Not provided' }}</td></tr>
                                    <tr><th>Status:</th><td>
                                        @if($student->familyData->mother_deceased) <span class="badge bg-danger">Deceased</span>
                                        @else <span class="badge bg-success">Living</span> @endif
                                    </td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <table class="info-table">
                                <tr><th>Parents' Marital Status:</th><td>{{ $student->familyData->parents_marital_status ?: 'Not provided' }}</td></tr>
                                <tr><th>Monthly Income:</th><td>{{ $student->familyData->family_monthly_income ?: 'Not provided' }}</td></tr>
                            </table>
                            <table class="info-table">
                                <tr><th>Ordinal Position:</th><td>{{ $student->familyData->ordinal_position ?: 'Not provided' }}</td></tr>
                                <tr><th>Siblings:</th><td>{{ $student->familyData->number_of_siblings ?: '0' }}</td></tr>
                            </table>
                        </div>
                        @if($student->familyData->guardian_name)
                            <h4 class="tab-section-title">Guardian Information</h4>
                            <table class="info-table mb-4">
                                <tr><th>Name:</th><td>{{ $student->familyData->guardian_name }}</td></tr>
                                <tr><th>Occupation:</th><td>{{ $student->familyData->guardian_occupation ?: 'Not provided' }}</td></tr>
                                <tr><th>Phone:</th><td>{{ $student->familyData->guardian_phone_number ?: 'Not provided' }}</td></tr>
                                <tr><th>Relationship:</th><td>{{ $student->familyData->guardian_relationship ?: 'Not provided' }}</td></tr>
                            </table>
                        @endif
                        @if($student->familyData->home_environment_description)
                            <h4 class="tab-section-title">Home Environment</h4>
                            <div class="text-card">{{ $student->familyData->home_environment_description }}</div>
                        @endif
                    @else
                        <p class="empty-state">No family data available</p>
                    @endif
                </div>

                <!-- Academic Data Tab -->
                <div class="tab-pane" id="academic">
                    @if($student->academicData)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <table class="info-table">
                                <tr><th>SHS GPA:</th><td>{{ $student->academicData->shs_gpa ?: 'Not provided' }}</td></tr>
                                <tr><th>Scholar:</th><td>
                                    @if($student->academicData->is_scholar) <span class="badge bg-success">Yes</span> @if($student->academicData->scholarship_type) ({{ $student->academicData->scholarship_type }}) @endif
                                    @else <span class="badge bg-secondary">No</span> @endif
                                </td></tr>
                                <tr><th>SHS Track:</th><td>{{ $student->academicData->shs_track ?: 'Not provided' }}</td></tr>
                                <tr><th>SHS Strand:</th><td>{{ $student->academicData->shs_strand ?: 'Not provided' }}</td></tr>
                            </table>
                            <table class="info-table">
                                <tr><th>Last School:</th><td>{{ $student->academicData->school_last_attended ?: 'Not provided' }}</td></tr>
                                <tr><th>School Address:</th><td>{{ $student->academicData->school_address ?: 'Not provided' }}</td></tr>
                                <tr><th>Course Choice By:</th><td>{{ $student->academicData->course_choice_by ?: 'Not provided' }}</td></tr>
                            </table>
                        </div>

                        @if($student->academicData->career_option_1 || $student->academicData->career_option_2 || $student->academicData->career_option_3)
                            <h4 class="tab-section-title">Career Options</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                                @if($student->academicData->career_option_1)
                                    <div class="text-card text-center">
                                        <small class="text-[var(--text-muted)]">Primary</small>
                                        <div class="font-semibold">{{ $student->academicData->career_option_1 }}</div>
                                    </div>
                                @endif
                                @if($student->academicData->career_option_2)
                                    <div class="text-card text-center">
                                        <small class="text-[var(--text-muted)]">Secondary</small>
                                        <div class="font-semibold">{{ $student->academicData->career_option_2 }}</div>
                                    </div>
                                @endif
                                @if($student->academicData->career_option_3)
                                    <div class="text-card text-center">
                                        <small class="text-[var(--text-muted)]">Tertiary</small>
                                        <div class="font-semibold">{{ $student->academicData->career_option_3 }}</div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @php
                            $awards = $student->academicData ? $student->academicData->awards_honors : null;
                            if (is_string($awards)) { $decoded = json_decode($awards, true); $awards = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $awards))); }
                            $orgs = $student->academicData ? $student->academicData->student_organizations : null;
                            if (is_string($orgs)) { $decoded = json_decode($orgs, true); $orgs = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $orgs))); }
                            $activities = $student->academicData ? $student->academicData->co_curricular_activities : null;
                            if (is_string($activities)) { $decoded = json_decode($activities, true); $activities = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $activities))); }
                            $reasons = $student->academicData ? $student->academicData->msu_choice_reasons : null;
                            if (is_string($reasons)) { $decoded = json_decode($reasons, true); $reasons = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $reasons))); }
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <h5 class="tab-section-title">Awards & Honors</h5>
                                @if($awards && count($awards)) <div class="badge-cloud">@foreach($awards as $a)<span class="badge bg-warning">{{ $a }}</span>@endforeach</div>
                                @else <span class="empty-state">Not provided</span> @endif
                            </div>
                            <div>
                                <h5 class="tab-section-title">Organizations</h5>
                                @if($orgs && count($orgs)) <div class="badge-cloud">@foreach($orgs as $o)<span class="badge bg-info">{{ $o }}</span>@endforeach</div>
                                @else <span class="empty-state">Not provided</span> @endif
                            </div>
                            <div>
                                <h5 class="tab-section-title">Activities</h5>
                                @if($activities && count($activities)) <div class="badge-cloud">@foreach($activities as $a)<span class="badge bg-success">{{ $a }}</span>@endforeach</div>
                                @else <span class="empty-state">Not provided</span> @endif
                            </div>
                        </div>
                        @if($reasons && count($reasons))
                            <h5 class="tab-section-title">MSU Choice Reasons</h5>
                            <div class="badge-cloud mb-4">@foreach($reasons as $r)<span class="badge bg-primary">{{ $r }}</span>@endforeach</div>
                        @endif
                        @if($student->academicData->future_career_plans)
                            <h4 class="tab-section-title">Future Career Plans</h4>
                            <div class="text-card mb-4">{{ $student->academicData->future_career_plans }}</div>
                        @endif
                        @if($student->academicData->course_choice_reason)
                            <h4 class="tab-section-title">Reason for Course Choice</h4>
                            <div class="text-card">{{ $student->academicData->course_choice_reason }}</div>
                        @endif
                    @else
                        <p class="empty-state">No academic data available</p>
                    @endif
                </div>

                <!-- Learning Resources Tab -->
                <div class="tab-pane" id="learning">
                    @if($student->learningResources)
                        <table class="info-table mb-4">
                            <tr>
                                <th>Internet Access:</th>
                                <td>
                                    @if($student->learningResources->internet_access)
                                        <span class="badge {{ $student->learningResources->internet_access == 'no internet access' ? 'bg-danger' : ($student->learningResources->internet_access == 'limited internet access' ? 'bg-warning' : 'bg-success') }}">
                                            {{ $student->learningResources->internet_access }}
                                        </span>
                                    @else <span class="empty-state">Not provided</span> @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Distance Learning Readiness:</th>
                                <td>
                                    @if($student->learningResources->distance_learning_readiness)
                                        <span class="badge {{ $student->learningResources->distance_learning_readiness == 'fully ready' ? 'bg-success' : ($student->learningResources->distance_learning_readiness == 'ready' ? 'bg-info' : ($student->learningResources->distance_learning_readiness == 'a little ready' ? 'bg-warning' : 'bg-danger')) }}">
                                            {{ $student->learningResources->distance_learning_readiness }}
                                        </span>
                                    @else <span class="empty-state">Not provided</span> @endif
                                </td>
                            </tr>
                        </table>
                        @php
                            $gadgets = $student->learningResources ? $student->learningResources->technology_gadgets : null;
                            if (is_string($gadgets)) { $decoded = json_decode($gadgets, true); $gadgets = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $gadgets))); }
                            $connectivity = $student->learningResources ? $student->learningResources->internet_connectivity : null;
                            if (is_string($connectivity)) { $decoded = json_decode($connectivity, true); $connectivity = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $connectivity))); }
                        @endphp
                        @if($gadgets && count($gadgets))
                            <h4 class="tab-section-title">Technology Gadgets</h4>
                            <div class="badge-cloud mb-4">@foreach($gadgets as $g)<span class="badge bg-primary"><i class="fas fa-mobile-alt mr-1"></i>{{ $g }}</span>@endforeach</div>
                        @endif
                        @if($connectivity && count($connectivity))
                            <h4 class="tab-section-title">Internet Connectivity</h4>
                            <div class="badge-cloud mb-4">@foreach($connectivity as $c)<span class="badge bg-success">{{ $c }}</span>@endforeach</div>
                        @endif
                        @if($student->learningResources->learning_space_description)
                            <h4 class="tab-section-title">Learning Space</h4>
                            <div class="text-card">{{ $student->learningResources->learning_space_description }}</div>
                        @endif
                    @else
                        <p class="empty-state">No learning resources data available</p>
                    @endif
                </div>

                <!-- Psychosocial Data Tab -->
                <div class="tab-pane" id="psychosocial">
                    @if($student->psychosocialData)
                        @if($student->psychosocialData->needs_immediate_counseling)
                            <div class="alert alert-danger mb-4">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Needs Immediate Counseling</strong>
                            </div>
                        @endif
                        <table class="info-table mb-4">
                            <tr><th>Had Counseling Before:</th><td>@if($student->psychosocialData->had_counseling_before)<span class="badge bg-info">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td></tr>
                            <tr><th>Sought Psychologist Help:</th><td>@if($student->psychosocialData->sought_psychologist_help)<span class="badge bg-info">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td></tr>
                            <tr><th>Needs Immediate Counseling:</th><td>@if($student->psychosocialData->needs_immediate_counseling)<span class="badge bg-danger">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td></tr>
                        </table>
                        @php
                            $personality = $student->psychosocialData ? $student->psychosocialData->personality_characteristics : null;
                            if (is_string($personality)) { $decoded = json_decode($personality, true); $personality = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $personality))); }
                            $coping = $student->psychosocialData ? $student->psychosocialData->coping_mechanisms : null;
                            if (is_string($coping)) { $decoded = json_decode($coping, true); $coping = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $coping))); }
                            $sharing = $student->psychosocialData ? $student->psychosocialData->problem_sharing_targets : null;
                            if (is_string($sharing)) { $decoded = json_decode($sharing, true); $sharing = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $sharing))); }
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <h5 class="tab-section-title">Personality Characteristics</h5>
                                @if($personality && count($personality)) <div class="badge-cloud">@foreach($personality as $p)<span class="badge bg-primary">{{ $p }}</span>@endforeach</div>
                                @else <span class="empty-state">Not provided</span> @endif
                            </div>
                            <div>
                                <h5 class="tab-section-title">Coping Mechanisms</h5>
                                @if($coping && count($coping)) <div class="badge-cloud">@foreach($coping as $c)<span class="badge bg-success">{{ $c }}</span>@endforeach</div>
                                @else <span class="empty-state">Not provided</span> @endif
                            </div>
                        </div>
                        @if($sharing && count($sharing))
                            <h5 class="tab-section-title">Problem Sharing Targets</h5>
                            <div class="badge-cloud mb-4">@foreach($sharing as $s)<span class="badge bg-warning">{{ $s }}</span>@endforeach</div>
                        @endif
                        @if($student->psychosocialData->mental_health_perception)
                            <h4 class="tab-section-title">Mental Health Perception</h4>
                            <div class="text-card mb-4">{{ $student->psychosocialData->mental_health_perception }}</div>
                        @endif
                        @if($student->psychosocialData->future_counseling_concerns)
                            <h4 class="tab-section-title">Counseling Concerns</h4>
                            <div class="text-card">{{ $student->psychosocialData->future_counseling_concerns }}</div>
                        @endif
                    @else
                        <p class="empty-state">No psychosocial data available</p>
                    @endif
                </div>

                <!-- Needs Assessment Tab -->
                <div class="tab-pane" id="needs">
                    @if($student->needsAssessment)
                        <table class="info-table mb-4">
                            <tr><th>Easy Discussion Target:</th><td>{{ $student->needsAssessment->easy_discussion_target ?: 'Not provided' }}</td></tr>
                        </table>
                        @php
                            $improvement = $student->needsAssessment ? $student->needsAssessment->improvement_needs : null;
                            if (is_string($improvement)) { $decoded = json_decode($improvement, true); $improvement = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $improvement))); }
                            $financial = $student->needsAssessment ? $student->needsAssessment->financial_assistance_needs : null;
                            if (is_string($financial)) { $decoded = json_decode($financial, true); $financial = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $financial))); }
                            $social = $student->needsAssessment ? $student->needsAssessment->personal_social_needs : null;
                            if (is_string($social)) { $decoded = json_decode($social, true); $social = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $social))); }
                            $stress = $student->needsAssessment ? $student->needsAssessment->stress_responses : null;
                            if (is_string($stress)) { $decoded = json_decode($stress, true); $stress = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $stress))); }
                            $perceptions = $student->needsAssessment ? $student->needsAssessment->counseling_perceptions : null;
                            if (is_string($perceptions)) { $decoded = json_decode($perceptions, true); $perceptions = is_array($decoded) ? $decoded : []; }
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <h5 class="tab-section-title">Needs to Improve</h5>
                                @if($improvement && count($improvement)) <div class="badge-cloud">@foreach($improvement as $n)<span class="badge bg-primary">{{ $n }}</span>@endforeach</div>
                                @else <span class="empty-state">Not provided</span> @endif
                            </div>
                            <div>
                                <h5 class="tab-section-title">Financial Assistance</h5>
                                @if($financial && count($financial)) <div class="badge-cloud">@foreach($financial as $n)<span class="badge bg-warning">{{ $n }}</span>@endforeach</div>
                                @else <span class="empty-state">Not provided</span> @endif
                            </div>
                            <div>
                                <h5 class="tab-section-title">Personal-Social Needs</h5>
                                @if($social && count($social)) <div class="badge-cloud">@foreach($social as $n)<span class="badge bg-success">{{ $n }}</span>@endforeach</div>
                                @else <span class="empty-state">Not provided</span> @endif
                            </div>
                        </div>
                        @if($stress && count($stress))
                            <h5 class="tab-section-title">Stress Responses</h5>
                            <div class="badge-cloud mb-4">@foreach($stress as $s)<span class="badge bg-danger">{{ $s }}</span>@endforeach</div>
                        @endif
                        <h5 class="tab-section-title">Counseling Perceptions</h5>
                        <table class="info-table">
                            @php
                                $statements = [
                                    'I willfully came for counseling when I had a problem.',
                                    'I experienced counseling upon referral by teachers, friends, parents, etc.',
                                    'I know that help is available at the Guidance and Counseling Center of MSU-IIT.',
                                    'I am afraid to go to the Guidance and Counseling Center of MSU-IIT.',
                                    'I am shy to ask assistance/seek counseling from my guidance counselor.'
                                ];
                            @endphp
                            @foreach($statements as $index => $statement)
                                @php $value = $perceptions[$index] ?? $perceptions[$statement] ?? null; @endphp
                                <tr><th>{{ $statement }}</th><td class="text-capitalize">{{ $value ?: 'Not provided' }}</td></tr>
                            @endforeach
                        </table>
                    @else
                        <p class="empty-state">No needs assessment data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Counseling Information Section -->
        @php
            $hasUrgentNeeds = $student->psychosocialData && $student->psychosocialData->needs_immediate_counseling;
            $counselingConcerns = [];
            if ($student->psychosocialData && $student->psychosocialData->future_counseling_concerns) {
                if (is_array($student->psychosocialData->future_counseling_concerns)) {
                    $counselingConcerns = $student->psychosocialData->future_counseling_concerns;
                } else {
                    $counselingConcerns = [$student->psychosocialData->future_counseling_concerns];
                }
            }
        @endphp
        @if($hasUrgentNeeds || count($counselingConcerns) > 0)
        <div class="card counseling-card">
            <div class="card-header"><i class="fas fa-exclamation-circle"></i> Counseling Information</div>
            <div class="card-body">
                @if($hasUrgentNeeds)
                    <div class="alert alert-danger mb-3">
                        <i class="fas fa-ambulance"></i>
                        <strong>This student requires immediate counseling attention.</strong>
                    </div>
                @endif
                @if(count($counselingConcerns) > 0)
                    <h4 class="tab-section-title">Counseling Concerns:</h4>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($counselingConcerns as $concern) <li>{{ $concern }}</li> @endforeach
                    </ul>
                @endif
            </div>
        </div>
        @endif

        <!-- Print Footer (visible only when printing) -->
        <div class="print-footer">
            Student Profile Report • {{ $student->full_name }} • Generated: {{ date('F j, Y g:i A') }} • Office of Guidance and Counseling
        </div>
    </div>
</div>

<script>
// Minimal tab switching
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            this.classList.add('active');
            const targetId = this.getAttribute('data-tab');
            document.getElementById(targetId).classList.add('active');
        });
    });
});
</script>
@endsection