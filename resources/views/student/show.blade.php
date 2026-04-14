@extends(Auth::check() && Auth::user()->role === 'counselor' ? 'layouts.app' : 'layouts.student')

@section('title', 'Student Details')

@section('content')
<style>
    .student-profile-shell {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(circle at top left, rgba(248, 101, 12, 0.08), transparent 24%),
            radial-gradient(circle at top right, rgba(130, 0, 0, 0.08), transparent 22%),
            linear-gradient(180deg, #fffaf8 0%, #f8fafc 45%, #f4f7fb 100%);
        min-height: 100vh;
    }

    .student-profile-glow {
        position: absolute;
        border-radius: 999px;
        filter: blur(72px);
        pointer-events: none;
        opacity: 0.55;
    }

    .student-profile-glow.one {
        top: -30px;
        left: -40px;
        width: 220px;
        height: 220px;
        background: rgba(248, 101, 12, 0.10);
    }

    .student-profile-glow.two {
        top: 260px;
        right: -80px;
        width: 260px;
        height: 260px;
        background: rgba(130, 0, 0, 0.10);
    }

    .student-details-page {
        position: relative;
        z-index: 1;
    }

    .soft-card,
    .hero-card,
    .tab-card,
    .soft-alert {
        border: 1px solid rgba(255,255,255,0.72);
        background: rgba(255,255,255,0.90);
        backdrop-filter: blur(12px);
        box-shadow: 0 18px 45px rgba(15,23,42,0.08);
        border-radius: 28px;
        overflow: hidden;
    }

    .hero-card,
    .tab-card,
    .soft-card {
        position: relative;
    }

    .hero-card::before,
    .tab-card::before,
    .soft-card::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: radial-gradient(circle at top right, rgba(248,101,12,0.08), transparent 28%);
    }

    .hero-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.5rem 1.5rem 1.35rem;
    }

    .hero-left {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        min-width: 0;
    }

    .hero-icon {
        width: 3.4rem;
        height: 3.4rem;
        border-radius: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        background: linear-gradient(135deg, #820000 0%, #b91c1c 55%, #f8650c 100%);
        box-shadow: 0 14px 30px rgba(130,0,0,0.24);
        flex-shrink: 0;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border-radius: 999px;
        border: 1px solid rgba(240,0,0,0.10);
        background: rgba(240,0,0,0.05);
        padding: 0.38rem 0.85rem;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        color: #820000;
    }

    .hero-badge-dot {
        width: 0.38rem;
        height: 0.38rem;
        border-radius: 999px;
        background: #f8650c;
    }

    .hero-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        margin-top: 0.8rem;
        margin-bottom: 0.35rem;
    }

    .hero-subtitle {
        color: #64748b;
        margin-bottom: 0;
        font-size: 0.98rem;
    }

    .print-btn {
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        padding: 0.8rem 1rem;
        border-radius: 1rem;
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(15,23,42,0.03);
        white-space: nowrap;
    }

    .print-btn:hover {
        background: linear-gradient(135deg, #820000 0%, #dc2626 55%, #f8650c 100%);
        color: #fff;
        border-color: transparent;
        transform: translateY(-1px);
    }

    .profile-warning {
        border-radius: 1.2rem;
        border: 1px solid #fde68a;
        background: linear-gradient(135deg, #fff7d6, #fffdf6);
        color: #8a5a00;
        box-shadow: 0 10px 24px rgba(146, 64, 14, 0.05);
    }

    .section-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(241,245,249,0.9);
        background: linear-gradient(90deg, #ffffff 0%, #fff8f6 100%);
    }

    .section-card-header h6 {
        margin: 0;
        font-weight: 700;
    }

    .profile-card-body {
        padding: 1.5rem;
    }

    .profile-image,
    .profile-fallback {
        width: 148px;
        height: 148px;
        object-fit: cover;
        border-radius: 999px;
        border: 5px solid rgba(255,255,255,0.9);
        box-shadow: 0 14px 30px rgba(15,23,42,0.10);
    }

    .profile-fallback {
        background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 100%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .student-name {
        font-size: 1.28rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .student-subid {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 0.9rem;
    }

    .student-chip-wrap {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.45rem;
        margin-bottom: 1.25rem;
    }

    .student-chip-wrap .badge {
        border-radius: 999px;
        padding: 0.52rem 0.8rem;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .quick-stat-box {
        background: linear-gradient(180deg, #fcfcfd, #f8fafc);
        border: 1px solid #eef2f7;
        border-radius: 1rem;
        padding: 0.85rem 0.75rem;
    }

    .quick-stat-box h6 {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
    }

    .contact-list strong {
        color: #334155;
        font-weight: 700;
    }

    .contact-list a {
        color: #dc2626;
        text-decoration: none;
    }

    .contact-list a:hover {
        color: #991b1b;
        text-decoration: underline;
    }

    .tab-card .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(241,245,249,0.9);
        padding: 0;
    }

    .student-tabs-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }

    .student-tabs-wrap::-webkit-scrollbar {
        height: 6px;
    }

    .student-tabs-wrap::-webkit-scrollbar-thumb {
        background: rgba(148,163,184,0.5);
        border-radius: 999px;
    }

    #studentTabs {
        flex-wrap: nowrap;
        white-space: nowrap;
        gap: 0;
        min-width: max-content;
        padding: 0.4rem 0.5rem 0;
    }

    #studentTabs .nav-item {
        flex: 0 0 auto;
    }

    #studentTabs .nav-link {
        border: 0;
        border-bottom: 2px solid transparent;
        color: #64748b;
        font-weight: 600;
        padding: 1rem 1rem 0.95rem;
        border-radius: 0.9rem 0.9rem 0 0;
        transition: all 0.2s ease;
        background: transparent;
    }

    #studentTabs .nav-link:hover {
        color: #820000;
        background: rgba(248,250,252,0.8);
    }

    #studentTabs .nav-link.active {
        color: #F00000;
        font-weight: 700;
        border-bottom-color: #F00000;
        background: linear-gradient(180deg, rgba(240,0,0,0.04), rgba(255,255,255,0));
    }

    .tab-body {
        padding: 1.4rem;
    }

    .tab-section-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.9rem;
        letter-spacing: -0.01em;
    }

    .info-table {
        margin-bottom: 0;
        color: #334155;
        border-color: #eef2f7;
    }

    .info-table th {
        font-weight: 700;
        color: #475569;
        background: transparent;
        padding: 0.75rem 0.5rem 0.75rem 0;
    }

    .info-table td {
        color: #0f172a;
        padding: 0.75rem 0;
        vertical-align: top;
    }

    .soft-info-box {
        border-radius: 1rem;
        background: linear-gradient(180deg, #fafafa, #f8fafc);
        border: 1px solid #e5e7eb;
    }

    .soft-info-box .card-body {
        padding: 1rem;
    }

    .soft-text-card {
        border-radius: 1rem;
        background: linear-gradient(180deg, #fcfcfd, #f8fafc);
        border: 1px solid #e5e7eb;
    }

    .soft-text-card .card-body {
        color: #334155;
        line-height: 1.65;
    }

    .badge-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .badge-cloud .badge {
        padding: 0.5rem 0.72rem;
        font-size: 0.72rem;
        border-radius: 999px;
        font-weight: 700;
    }

    .counseling-card {
        border-radius: 1.25rem;
        border: 1px solid #fcd34d;
        background: linear-gradient(135deg, #fff8dc, #fffef7);
        box-shadow: 0 10px 24px rgba(146, 64, 14, 0.05);
        overflow: hidden;
    }

    .counseling-card .card-header {
        border-bottom: 1px solid rgba(146, 64, 14, 0.08);
    }

    .mobile-stack-gap > [class*="col-"] {
        margin-bottom: 1rem;
    }

    @media (max-width: 991.98px) {
        .hero-top {
            flex-direction: column;
            align-items: stretch;
        }

        .hero-title {
            font-size: 1.7rem;
        }

        .print-btn {
            width: 100%;
            justify-content: center;
        }

        .tab-body {
            padding: 1.1rem;
        }
    }

    @media (max-width: 767.98px) {
        .student-details-page {
            padding-left: 0.35rem;
            padding-right: 0.35rem;
        }

        .hero-top {
            padding: 1.1rem 1rem 1rem;
        }

        .hero-left {
            gap: 0.8rem;
        }

        .hero-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.9rem;
        }

        .hero-title {
            font-size: 1.45rem;
            margin-top: 0.7rem;
        }

        .profile-card-body,
        .tab-body,
        .section-card-header,
        .contact-card-body {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .profile-image,
        .profile-fallback {
            width: 118px;
            height: 118px;
        }

        .student-name {
            font-size: 1.12rem;
        }

        #studentTabs .nav-link {
            padding: 0.9rem 0.85rem 0.85rem;
            font-size: 0.9rem;
        }

        .info-table {
            font-size: 0.92rem;
        }

        .quick-stat-box {
            margin-bottom: 0.75rem;
        }

        .badge-cloud .badge {
            font-size: 0.7rem;
        }
    }

    @media print {
        .btn-group,
        .profile-warning,
        .nav-tabs,
        .student-tabs-wrap,
        .print-btn {
            display: none !important;
        }

        .soft-card,
        .hero-card,
        .tab-card,
        .soft-alert,
        .counseling-card {
            box-shadow: none !important;
            backdrop-filter: none !important;
            border: 1px solid #000 !important;
            background: #fff !important;
        }

        .tab-pane {
            display: block !important;
            opacity: 1 !important;
        }

        .student-profile-shell {
            background: #fff !important;
        }
    }
</style>

<div class="student-profile-shell">
    <div class="student-profile-glow one"></div>
    <div class="student-profile-glow two"></div>

    <div class="container-fluid py-4 student-details-page">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-card">
                    <div class="hero-top">
                        <div class="hero-left">
                            <div class="hero-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <div class="hero-badge">
                                    <span class="hero-badge-dot"></span>
                                    Student Profile
                                </div>
                                <h1 class="hero-title">Student Profile</h1>
                                <p class="hero-subtitle">A complete and easy-to-read view of the student's profile, background, and support information.</p>
                            </div>
                        </div>

                        <div>
                            <div class="btn-group">
                                <button class="btn print-btn" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Completion Alert -->
        @if($student->profile_completion['percentage'] < 100)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert profile-warning mb-0">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle me-3 mt-1 fa-lg"></i>
                        <div class="flex-grow-1">
                            <strong>Profile Incomplete:</strong> This student's profile is {{ $student->profile_completion['percentage'] }}% complete.
                            Missing sections:
                            @foreach($student->profile_completion['sections'] as $section => $completed)
                                @if(!$completed)
                                    <span class="badge bg-danger ms-1">{{ ucfirst($section) }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Student Basic Information -->
        <div class="row mb-4 mobile-stack-gap">
            <div class="col-lg-4">
                <!-- Profile Card -->
                <div class="soft-card mb-4">
                    <div class="profile-card-body text-center">
                        @if($student->profile_picture)
                            <img src="{{ $student->profile_picture_url }}"
                                 alt="Profile Picture"
                                 class="profile-image mb-3">
                        @else
                            <div class="profile-fallback mb-3">
                                <i class="fas fa-user fa-3x text-secondary"></i>
                            </div>
                        @endif

                        <h3 class="student-name">{{ $student->full_name }}</h3>
                        <p class="student-subid">{{ $student->student_id }}</p>

                        <div class="student-chip-wrap">
                            <span class="badge bg-primary">{{ $student->year_level }}</span>
                            <span class="badge bg-secondary">{{ $student->course }}</span>
                            <span class="badge bg-info">{{ $student->student_status }}</span>
                        </div>

                        <!-- Quick Stats -->
                        <div class="row text-center mt-2 g-2">
                            <div class="col-6">
                                <div class="quick-stat-box h-100">
                                    <h6 class="mb-0">{{ $student->registration_count }}</h6>
                                    <small class="text-muted">Events</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="quick-stat-box h-100">
                                    <h6 class="mb-0">{{ $student->appointments->count() }}</h6>
                                    <small class="text-muted">Appointments</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="soft-card">
                    <div class="section-card-header bg-primary text-white">
                        <h6><i class="fas fa-address-card me-2"></i>Contact Information</h6>
                    </div>
                    <div class="card-body contact-card-body contact-list p-4">
                        <div class="mb-3">
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                        </div>
                        <div class="mb-3">
                            <strong>Phone:</strong><br>
                            {{ $student->phone_number ?: 'Not provided' }}
                        </div>
                        <div class="mb-3">
                            <strong>Address:</strong><br>
                            {{ $student->user->address ?? 'Not provided' }}
                        </div>
                        <div>
                            <strong>College:</strong><br>
                            {{ $student->college->name ?? 'Not assigned' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Main Details Tabs -->
                <div class="tab-card">
                    <div class="card-header bg-white">
                        <div class="student-tabs-wrap">
                            <ul class="nav nav-tabs card-header-tabs" id="studentTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab"
                                            data-bs-target="#personal" type="button" role="tab" aria-controls="personal"
                                            aria-selected="true">
                                        <i class="fas fa-user me-2"></i>Personal
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="family-tab" data-bs-toggle="tab"
                                            data-bs-target="#family" type="button" role="tab" aria-controls="family"
                                            aria-selected="false">
                                        <i class="fas fa-home me-2"></i>Family
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="academic-tab" data-bs-toggle="tab"
                                            data-bs-target="#academic" type="button" role="tab" aria-controls="academic"
                                            aria-selected="false">
                                        <i class="fas fa-graduation-cap me-2"></i>Academic
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="learning-tab" data-bs-toggle="tab"
                                            data-bs-target="#learning" type="button" role="tab" aria-controls="learning"
                                            aria-selected="false">
                                        <i class="fas fa-laptop me-2"></i>Learning
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="psychosocial-tab" data-bs-toggle="tab"
                                            data-bs-target="#psychosocial" type="button" role="tab" aria-controls="psychosocial"
                                            aria-selected="false">
                                        <i class="fas fa-brain me-2"></i>Psychosocial
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="needs-tab" data-bs-toggle="tab"
                                            data-bs-target="#needs" type="button" role="tab" aria-controls="needs"
                                            aria-selected="false">
                                        <i class="fas fa-clipboard-list me-2"></i>Needs
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body tab-body">
                        <div class="tab-content" id="studentTabsContent">

                            <!-- Personal Data Tab -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <h6 class="tab-section-title">Basic Information</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm info-table">
                                            <tr>
                                                <th width="40%">First Name:</th>
                                                <td>{{ $student->user->first_name ?? 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Middle Name:</th>
                                                <td>{{ $student->user->middle_name ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Last Name:</th>
                                                <td>{{ $student->user->last_name ?? 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Birthdate:</th>
                                                <td>{{ $student->user->birthdate ? \Carbon\Carbon::parse($student->user->birthdate)->format('M d, Y') : 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Age:</th>
                                                <td>{{ $student->user->age ?? 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Sex:</th>
                                                <td>{{ $student->user->sex ?: 'Not provided' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm info-table">
                                            <tr>
                                                <th width="40%">Birthplace:</th>
                                                <td>{{ $student->user->birthplace ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Religion:</th>
                                                <td>{{ $student->user->religion ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Civil Status:</th>
                                                <td>{{ $student->user->civil_status ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Number of Children:</th>
                                                <td>{{ $student->user->number_of_children ?? 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Citizenship:</th>
                                                <td>{{ $student->user->citizenship ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>{{ $student->email ?: 'Not provided' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <h6 class="tab-section-title">Registration Details</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm info-table">
                                            <tr>
                                                <th width="40%">Student ID:</th>
                                                <td>{{ $student->student_id ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Year Level:</th>
                                                <td>{{ $student->year_level ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Course:</th>
                                                <td>{{ $student->course ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>College:</th>
                                                <td>{{ $student->college->name ?? 'Not assigned' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm info-table">
                                            <tr>
                                                <th width="40%">MSU SASE Score:</th>
                                                <td>{{ $student->msu_sase_score ?? 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Academic Year:</th>
                                                <td>{{ $student->academic_year ?: 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Student Status:</th>
                                                <td>{{ $student->student_status ?: 'Not provided' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @if($student->personalData)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="40%">Nickname:</th>
                                                    <td>{{ $student->personalData->nickname ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Home Address:</th>
                                                    <td>{{ $student->personalData->home_address ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Stays With:</th>
                                                    <td>{{ $student->personalData->stays_with ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Working Student:</th>
                                                    <td>{{ $student->personalData->working_student ?: 'Not provided' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="40%">Sex Identity:</th>
                                                    <td>{{ $student->personalData->sex_identity ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Romantic Attraction:</th>
                                                    <td>{{ $student->personalData->romantic_attraction ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Medical Condition:</th>
                                                    <td>{{ $student->personalData->serious_medical_condition ?: 'None' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Physical Disability:</th>
                                                    <td>{{ $student->personalData->physical_disability ?: 'None' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Arrays Data -->
                                    @php
                                        $talentsSkills = $student->personalData ? $student->personalData->talents_skills : null;
                                        if (is_string($talentsSkills)) {
                                            $decoded = json_decode($talentsSkills, true);
                                            $talentsSkills = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $talentsSkills)));
                                        }

                                        $leisureActivities = $student->personalData ? $student->personalData->leisure_activities : null;
                                        if (is_string($leisureActivities)) {
                                            $decoded = json_decode($leisureActivities, true);
                                            $leisureActivities = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $leisureActivities)));
                                        }
                                    @endphp
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <h6 class="tab-section-title">Talents & Skills</h6>
                                            @if($talentsSkills && count($talentsSkills))
                                                <div class="badge-cloud">
                                                    @foreach($talentsSkills as $skill)
                                                        <span class="badge bg-primary">{{ $skill }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <h6 class="tab-section-title">Leisure Activities</h6>
                                            @if($leisureActivities && count($leisureActivities))
                                                <div class="badge-cloud">
                                                    @foreach($leisureActivities as $activity)
                                                        <span class="badge bg-success">{{ $activity }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No personal data available</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Family Data Tab -->
                            <div class="tab-pane fade" id="family" role="tabpanel">
                                @if($student->familyData)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="tab-section-title">Father's Information</h6>
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="40%">Name:</th>
                                                    <td>{{ $student->familyData->father_name ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Occupation:</th>
                                                    <td>{{ $student->familyData->father_occupation ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone:</th>
                                                    <td>{{ $student->familyData->father_phone_number ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status:</th>
                                                    <td>
                                                        @if($student->familyData->father_deceased)
                                                            <span class="badge bg-danger">Deceased</span>
                                                        @else
                                                            <span class="badge bg-success">Living</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="tab-section-title">Mother's Information</h6>
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="40%">Name:</th>
                                                    <td>{{ $student->familyData->mother_name ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Occupation:</th>
                                                    <td>{{ $student->familyData->mother_occupation ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone:</th>
                                                    <td>{{ $student->familyData->mother_phone_number ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status:</th>
                                                    <td>
                                                        @if($student->familyData->mother_deceased)
                                                            <span class="badge bg-danger">Deceased</span>
                                                        @else
                                                            <span class="badge bg-success">Living</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="40%">Marital Status:</th>
                                                    <td>{{ $student->familyData->parents_marital_status ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Income:</th>
                                                    <td>{{ $student->familyData->family_monthly_income ?: 'Not provided' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="40%">Ordinal Position:</th>
                                                    <td>{{ $student->familyData->ordinal_position ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Number of Siblings:</th>
                                                    <td>{{ $student->familyData->number_of_siblings ?: '0' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    @if($student->familyData->guardian_name)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="tab-section-title">Guardian Information</h6>
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="20%">Name:</th>
                                                    <td>{{ $student->familyData->guardian_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Occupation:</th>
                                                    <td>{{ $student->familyData->guardian_occupation ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone:</th>
                                                    <td>{{ $student->familyData->guardian_phone_number ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Relationship:</th>
                                                    <td>{{ $student->familyData->guardian_relationship ?: 'Not provided' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    @endif

                                    @if($student->familyData->home_environment_description)
                                    <div class="mt-4">
                                        <h6 class="tab-section-title">Home Environment Description</h6>
                                        <div class="soft-text-card">
                                            <div class="card-body">
                                                {{ $student->familyData->home_environment_description }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-home fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No family data available</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Academic Data Tab -->
                            <div class="tab-pane fade" id="academic" role="tabpanel">
                                @if($student->academicData)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="40%">SHS GPA:</th>
                                                    <td>{{ $student->academicData->shs_gpa ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Scholar:</th>
                                                    <td>
                                                        @if($student->academicData->is_scholar)
                                                            <span class="badge bg-success">Yes</span>
                                                            @if($student->academicData->scholarship_type)
                                                                ({{ $student->academicData->scholarship_type }})
                                                            @endif
                                                        @else
                                                            <span class="badge bg-secondary">No</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>SHS Track:</th>
                                                    <td>{{ $student->academicData->shs_track ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>SHS Strand:</th>
                                                    <td>{{ $student->academicData->shs_strand ?: 'Not provided' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="40%">Last School:</th>
                                                    <td>{{ $student->academicData->school_last_attended ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>School Address:</th>
                                                    <td>{{ $student->academicData->school_address ?: 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Course Choice By:</th>
                                                    <td>{{ $student->academicData->course_choice_by ?: 'Not provided' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Career Options -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="tab-section-title">Career Options</h6>
                                            <div class="row g-3">
                                                @if($student->academicData->career_option_1)
                                                <div class="col-md-4">
                                                    <div class="soft-info-box h-100">
                                                        <div class="card-body text-center">
                                                            <small class="text-muted">Primary Choice</small>
                                                            <div class="fw-bold">{{ $student->academicData->career_option_1 }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($student->academicData->career_option_2)
                                                <div class="col-md-4">
                                                    <div class="soft-info-box h-100">
                                                        <div class="card-body text-center">
                                                            <small class="text-muted">Secondary Choice</small>
                                                            <div class="fw-bold">{{ $student->academicData->career_option_2 }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($student->academicData->career_option_3)
                                                <div class="col-md-4">
                                                    <div class="soft-info-box h-100">
                                                        <div class="card-body text-center">
                                                            <small class="text-muted">Tertiary Choice</small>
                                                            <div class="fw-bold">{{ $student->academicData->career_option_3 }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Academic Data Arrays -->
                                    @php
                                        $awardsHonors = $student->academicData ? $student->academicData->awards_honors : null;
                                        if (is_string($awardsHonors)) {
                                            $decoded = json_decode($awardsHonors, true);
                                            $awardsHonors = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $awardsHonors)));
                                        }

                                        $studentOrganizations = $student->academicData ? $student->academicData->student_organizations : null;
                                        if (is_string($studentOrganizations)) {
                                            $decoded = json_decode($studentOrganizations, true);
                                            $studentOrganizations = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $studentOrganizations)));
                                        }

                                        $coCurricularActivities = $student->academicData ? $student->academicData->co_curricular_activities : null;
                                        if (is_string($coCurricularActivities)) {
                                            $decoded = json_decode($coCurricularActivities, true);
                                            $coCurricularActivities = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $coCurricularActivities)));
                                        }

                                        $msuChoiceReasons = $student->academicData ? $student->academicData->msu_choice_reasons : null;
                                        if (is_string($msuChoiceReasons)) {
                                            $decoded = json_decode($msuChoiceReasons, true);
                                            $msuChoiceReasons = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $msuChoiceReasons)));
                                        }
                                    @endphp
                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <h6 class="tab-section-title">Awards & Honors</h6>
                                            @if($awardsHonors && count($awardsHonors))
                                                <div class="badge-cloud">
                                                    @foreach($awardsHonors as $award)
                                                        <span class="badge bg-warning text-dark">{{ $award }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>

                                        <div class="col-md-4">
                                            <h6 class="tab-section-title">Student Organizations</h6>
                                            @if($studentOrganizations && count($studentOrganizations))
                                                <div class="badge-cloud">
                                                    @foreach($studentOrganizations as $org)
                                                        <span class="badge bg-info">{{ $org }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>

                                        <div class="col-md-4">
                                            <h6 class="tab-section-title">Co-curricular Activities</h6>
                                            @if($coCurricularActivities && count($coCurricularActivities))
                                                <div class="badge-cloud">
                                                    @foreach($coCurricularActivities as $activity)
                                                        <span class="badge bg-success">{{ $activity }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="tab-section-title">MSU Choice Reasons</h6>
                                            @if($msuChoiceReasons && count($msuChoiceReasons))
                                                <div class="badge-cloud">
                                                    @foreach($msuChoiceReasons as $reason)
                                                        <span class="badge bg-primary">{{ $reason }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($student->academicData->future_career_plans)
                                    <div class="mt-4">
                                        <h6 class="tab-section-title">Future Career Plans</h6>
                                        <div class="soft-text-card">
                                            <div class="card-body">
                                                {{ $student->academicData->future_career_plans }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($student->academicData->course_choice_reason)
                                    <div class="mt-4">
                                        <h6 class="tab-section-title">Reason for Choosing the Course</h6>
                                        <div class="soft-text-card">
                                            <div class="card-body">
                                                {{ $student->academicData->course_choice_reason }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No academic data available</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Learning Resources Tab -->
                            <div class="tab-pane fade" id="learning" role="tabpanel">
                                @if($student->learningResources)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="40%">Internet Access:</th>
                                                    <td>
                                                        @if($student->learningResources->internet_access)
                                                            <span class="badge
                                                                @if($student->learningResources->internet_access == 'no internet access') bg-danger
                                                                @elseif($student->learningResources->internet_access == 'limited internet access') bg-warning text-dark
                                                                @else bg-success @endif">
                                                                {{ $student->learningResources->internet_access }}
                                                            </span>
                                                        @else
                                                            Not provided
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Distance Learning Readiness:</th>
                                                    <td>
                                                        @if($student->learningResources->distance_learning_readiness)
                                                            <span class="badge
                                                                @if($student->learningResources->distance_learning_readiness == 'fully ready') bg-success
                                                                @elseif($student->learningResources->distance_learning_readiness == 'ready') bg-info
                                                                @elseif($student->learningResources->distance_learning_readiness == 'a little ready') bg-warning text-dark
                                                                @else bg-danger @endif">
                                                                {{ $student->learningResources->distance_learning_readiness }}
                                                            </span>
                                                        @else
                                                            Not provided
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Technology Gadgets -->
                                    @php
                                        $technologyGadgets = $student->learningResources ? $student->learningResources->technology_gadgets : null;
                                        if (is_string($technologyGadgets)) {
                                            $decoded = json_decode($technologyGadgets, true);
                                            $technologyGadgets = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $technologyGadgets)));
                                        }

                                        $internetConnectivity = $student->learningResources ? $student->learningResources->internet_connectivity : null;
                                        if (is_string($internetConnectivity)) {
                                            $decoded = json_decode($internetConnectivity, true);
                                            $internetConnectivity = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $internetConnectivity)));
                                        }
                                    @endphp
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="tab-section-title">Technology Gadgets</h6>
                                            @if($technologyGadgets && count($technologyGadgets))
                                                <div class="badge-cloud">
                                                    @foreach($technologyGadgets as $gadget)
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-mobile-alt me-1"></i>{{ $gadget }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="tab-section-title">Means of Internet Connectivity</h6>
                                            @if($internetConnectivity && count($internetConnectivity))
                                                <div class="badge-cloud">
                                                    @foreach($internetConnectivity as $connectivity)
                                                        <span class="badge bg-success">{{ $connectivity }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($student->learningResources->learning_space_description)
                                    <div class="mt-4">
                                        <h6 class="tab-section-title">Learning Space Description</h6>
                                        <div class="soft-text-card">
                                            <div class="card-body">
                                                {{ $student->learningResources->learning_space_description }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-laptop fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No learning resources data available</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Psychosocial Data Tab -->
                            <div class="tab-pane fade" id="psychosocial" role="tabpanel">
                                @if($student->psychosocialData)
                                    <!-- Urgent Counseling Alert -->
                                    @if($student->psychosocialData->needs_immediate_counseling)
                                    <div class="alert alert-danger rounded-4">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Needs Immediate Counseling</strong>
                                    </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="40%">Had Counseling Before:</th>
                                                    <td>
                                                        @if($student->psychosocialData->had_counseling_before)
                                                            <span class="badge bg-info">Yes</span>
                                                        @else
                                                            <span class="badge bg-secondary">No</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Sought Psychologist Help:</th>
                                                    <td>
                                                        @if($student->psychosocialData->sought_psychologist_help)
                                                            <span class="badge bg-info">Yes</span>
                                                        @else
                                                            <span class="badge bg-secondary">No</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Need Immediate Counseling:</th>
                                                    <td>
                                                        @if($student->psychosocialData->needs_immediate_counseling)
                                                            <span class="badge bg-danger">Yes</span>
                                                        @else
                                                            <span class="badge bg-secondary">No</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Arrays Data -->
                                    @php
                                        $personalityCharacteristics = $student->psychosocialData ? $student->psychosocialData->personality_characteristics : null;
                                        if (is_string($personalityCharacteristics)) {
                                            $decoded = json_decode($personalityCharacteristics, true);
                                            $personalityCharacteristics = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $personalityCharacteristics)));
                                        }

                                        $copingMechanisms = $student->psychosocialData ? $student->psychosocialData->coping_mechanisms : null;
                                        if (is_string($copingMechanisms)) {
                                            $decoded = json_decode($copingMechanisms, true);
                                            $copingMechanisms = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $copingMechanisms)));
                                        }

                                        $problemSharingTargets = $student->psychosocialData ? $student->psychosocialData->problem_sharing_targets : null;
                                        if (is_string($problemSharingTargets)) {
                                            $decoded = json_decode($problemSharingTargets, true);
                                            $problemSharingTargets = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $problemSharingTargets)));
                                        }
                                    @endphp
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <h6 class="tab-section-title">Personality Characteristics</h6>
                                            @if($personalityCharacteristics && count($personalityCharacteristics))
                                                <div class="badge-cloud">
                                                    @foreach($personalityCharacteristics as $characteristic)
                                                        <span class="badge bg-primary">{{ $characteristic }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <h6 class="tab-section-title">Coping Mechanisms</h6>
                                            @if($copingMechanisms && count($copingMechanisms))
                                                <div class="badge-cloud">
                                                    @foreach($copingMechanisms as $mechanism)
                                                        <span class="badge bg-success">{{ $mechanism }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="tab-section-title">Problem Sharing Targets</h6>
                                            @if($problemSharingTargets && count($problemSharingTargets))
                                                <div class="badge-cloud">
                                                    @foreach($problemSharingTargets as $target)
                                                        <span class="badge bg-warning text-dark">{{ $target }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($student->psychosocialData->mental_health_perception)
                                    <div class="mt-4">
                                        <h6 class="tab-section-title">Mental Health Perception</h6>
                                        <div class="soft-text-card">
                                            <div class="card-body">
                                                {{ $student->psychosocialData->mental_health_perception }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($student->psychosocialData->future_counseling_concerns)
                                    <div class="mt-4">
                                        <h6 class="tab-section-title">Future Counseling Concerns</h6>
                                        <div class="soft-text-card">
                                            <div class="card-body">
                                                {{ $student->psychosocialData->future_counseling_concerns }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-brain fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No psychosocial data available</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Needs Assessment Tab -->
                            <div class="tab-pane fade" id="needs" role="tabpanel">
                                @if($student->needsAssessment)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm info-table">
                                                <tr>
                                                    <th width="40%">Easy Discussion Target:</th>
                                                    <td>{{ $student->needsAssessment->easy_discussion_target ?: 'Not provided' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Arrays Data -->
                                    @php
                                        $improvementNeeds = $student->needsAssessment ? $student->needsAssessment->improvement_needs : null;
                                        if (is_string($improvementNeeds)) {
                                            $decoded = json_decode($improvementNeeds, true);
                                            $improvementNeeds = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $improvementNeeds)));
                                        }

                                        $financialAssistanceNeeds = $student->needsAssessment ? $student->needsAssessment->financial_assistance_needs : null;
                                        if (is_string($financialAssistanceNeeds)) {
                                            $decoded = json_decode($financialAssistanceNeeds, true);
                                            $financialAssistanceNeeds = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $financialAssistanceNeeds)));
                                        }

                                        $personalSocialNeeds = $student->needsAssessment ? $student->needsAssessment->personal_social_needs : null;
                                        if (is_string($personalSocialNeeds)) {
                                            $decoded = json_decode($personalSocialNeeds, true);
                                            $personalSocialNeeds = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $personalSocialNeeds)));
                                        }

                                        $stressResponses = $student->needsAssessment ? $student->needsAssessment->stress_responses : null;
                                        if (is_string($stressResponses)) {
                                            $decoded = json_decode($stressResponses, true);
                                            $stressResponses = is_array($decoded)
                                                ? $decoded
                                                : array_filter(array_map('trim', explode(',', $stressResponses)));
                                        }

                                        $counselingPerceptions = $student->needsAssessment ? $student->needsAssessment->counseling_perceptions : null;
                                        if (is_string($counselingPerceptions)) {
                                            $decoded = json_decode($counselingPerceptions, true);
                                            $counselingPerceptions = is_array($decoded)
                                                ? $decoded
                                                : [];
                                        }
                                    @endphp
                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <h6 class="tab-section-title">Need to Improve the Following</h6>
                                            @if($improvementNeeds && count($improvementNeeds))
                                                <div class="badge-cloud">
                                                    @foreach($improvementNeeds as $need)
                                                        <span class="badge bg-primary">{{ $need }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>

                                        <div class="col-md-4">
                                            <h6 class="tab-section-title">Assistance Needs</h6>
                                            @if($financialAssistanceNeeds && count($financialAssistanceNeeds))
                                                <div class="badge-cloud">
                                                    @foreach($financialAssistanceNeeds as $need)
                                                        <span class="badge bg-warning text-dark">{{ $need }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>

                                        <div class="col-md-4">
                                            <h6 class="tab-section-title">Personal-Social Assistance</h6>
                                            @if($personalSocialNeeds && count($personalSocialNeeds))
                                                <div class="badge-cloud">
                                                    @foreach($personalSocialNeeds as $need)
                                                        <span class="badge bg-success">{{ $need }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="tab-section-title">Stress Responses</h6>
                                            @if($stressResponses && count($stressResponses))
                                                <div class="badge-cloud">
                                                    @foreach($stressResponses as $response)
                                                        <span class="badge bg-danger">{{ $response }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="tab-section-title">Counseling Perceptions</h6>
                                            @php
                                                $counselingStatements = [
                                                    'I willfully came for counseling when I had a problem.',
                                                    'I experienced counseling upon referral by teachers, friends, parents, etc.',
                                                    'I know that help is available at the Guidance and Counseling Center of MSU-IIT.',
                                                    'I am afraid to go to the Guidance and Counseling Center of MSU-IIT.',
                                                    'I am shy to ask assistance/seek counseling from my guidance counselor.'
                                                ];
                                            @endphp
                                            <table class="table table-sm info-table">
                                                @foreach($counselingStatements as $index => $statement)
                                                    @php
                                                        $perceptionValue = $counselingPerceptions[$index]
                                                            ?? $counselingPerceptions[$statement]
                                                            ?? null;
                                                    @endphp
                                                    <tr>
                                                        <th width="70%">{{ $statement }}</th>
                                                        <td class="text-capitalize">
                                                            {{ $perceptionValue ?: 'Not provided' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No needs assessment data available</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Counseling Information Section - Temporary Safe Version -->
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
        <div class="row mb-4">
            <div class="col-12">
                <div class="counseling-card">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Counseling Information</h6>
                    </div>
                    <div class="card-body p-4">
                        @if($hasUrgentNeeds)
                        <div class="alert alert-danger rounded-4">
                            <i class="fas fa-ambulance me-2"></i>
                            <strong>This student requires immediate counseling attention.</strong>
                        </div>
                        @endif

                        @if(count($counselingConcerns) > 0)
                        <h6 class="tab-section-title mb-3">Counseling Concerns:</h6>
                        <ul class="list-group">
                            @foreach($counselingConcerns as $concern)
                                <li class="list-group-item">{{ $concern }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    font-weight: 600;
}

.table th {
    font-weight: 600;
    color: #495057;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.badge {
    font-size: 0.75em;
}

@media print {
    .btn-group, .alert-warning, .nav-tabs {
        display: none !important;
    }

    .card {
        border: 1px solid #000 !important;
    }

    .tab-pane {
        display: block !important;
        opacity: 1 !important;
    }
}
</style>

<script>
// Simple tab switching without Bootstrap JS
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('#studentTabs .nav-link');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('show', 'active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Show corresponding pane
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }
        });
    });
});
</script>
@endsection