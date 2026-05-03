@extends('layouts.admin')

@section('title', 'Students - Admin Panel')

@section('content')

<div class="students-shell relative overflow-hidden min-h-screen bg-[#faf8f5]">
    <div class="students-glow glow-one"></div>
    <div class="students-glow glow-two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        
        <!-- Header Section -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card group">
                    <div class="hero-card-pattern"></div>
                    <div class="relative flex items-start gap-3 p-4 sm:p-5">
                        <div class="hero-icon">
                            <i class="fas fa-user-graduate text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Student Directory
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Students</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Manage and oversee all student records in a cleaner, more polished admin workspace.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-card-pattern"></div>
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-users text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Total Students</p>
                                <p class="summary-value">{{ $totalStudents ?? $students->total() }}</p>
                                <p class="summary-subtext hidden sm:block">Live student directory count</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.create', ['role' => 'student']) }}"
                           class="primary-btn px-5 py-2.5 whitespace-nowrap text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i> Add Student
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students per College Card -->
        <div class="panel-card mb-5 sm:mb-6 overflow-hidden">
            <div class="panel-topline"></div>

            <div class="panel-header">
                <div class="panel-header-icon">
                    <i class="fas fa-school text-xs sm:text-sm"></i>
                </div>
                <div>
                    <h2 class="panel-title">Students per College</h2>
                    <p class="panel-subtitle hidden sm:block">Click a college to filter results</p>
                </div>
            </div>

            <div class="p-3 sm:p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    @foreach(($studentsPerCollege ?? []) as $collegeStat)
                        @php
                            $isActive = (string)($college ?? '') === (string)$collegeStat->id;
                        @endphp
                        <a href="{{ route('admin.students', array_filter(['search' => $search, 'college' => $collegeStat->id])) }}"
                           class="college-stat-card group {{ $isActive ? 'college-stat-active' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="text-left min-w-0 pr-2">
                                    <div class="college-stat-name" title="{{ $collegeStat->name }}">{{ $collegeStat->name }}</div>
                                    <div class="college-stat-count mt-1.5">{{ $collegeStat->students_count }}</div>
                                </div>
                                <div class="stats-icon {{ $isActive ? 'bg-[#fef9e7] text-[#c9a227]' : 'bg-[#eff6ff] text-sky-600 group-hover:bg-[#e0f2fe]' }} transition-colors">
                                    <i class="fas fa-school text-sm sm:text-base"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="panel-card mb-5 sm:mb-6">
            <div class="p-3 sm:p-4">
                <form method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 sm:left-3.5 top-1/2 -translate-y-1/2 text-[#a89f97] text-[10px] sm:text-xs"></i>
                            <input type="text" name="search" value="{{ $search }}"
                                   placeholder="    Search student ID, name, email, course..."
                                   class="input-field w-full pl-8 sm:pl-9 pr-3 py-2 sm:py-2.5 text-xs sm:text-sm">
                        </div>
                    </div>

                    <div class="w-full md:w-52 lg:w-60 min-w-0">
                        <select name="college" class="input-field w-full px-3 py-2 sm:py-2.5 bg-white text-[#4a3f3a] text-xs sm:text-sm">
                            <option value="" {{ empty($college) ? 'selected' : '' }}>All Colleges</option>
                            @foreach($colleges as $c)
                                <option value="{{ $c->id }}" {{ (string)($college ?? '') === (string)$c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="search-btn w-full md:w-auto px-4 py-2 sm:py-2.5 font-medium flex items-center justify-center gap-2 text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-search text-[10px] sm:text-xs"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Students Table Card -->
        <div class="panel-card overflow-hidden">
            <div class="table-header-bar">
                <div class="flex items-center gap-3">
                    <div class="table-header-icon">
                        <i class="fas fa-users text-[#7a2a2a] text-[10px] sm:text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-medium text-[#2c2420]">Student Directory</h2>
                        <p class="text-[10px] sm:text-xs text-[#8b7e76]">Showing <span class="font-bold text-[#2c2420]">{{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }}</span> of <span class="font-bold text-[#2c2420]">{{ $students->total() }}</span></p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[850px] md:min-w-full divide-y divide-[#e5e0db]/60 w-full">
                    <thead class="bg-[#faf8f5]/85">
                        <tr>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">ID</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Student</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Student ID</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">College / Counselor</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Course / Year</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Last Session</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Status</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                        @forelse($students as $student)
                            <tr class="table-row group cursor-pointer" onclick="window.location='{{ route('admin.students.profile', $student) }}'">
                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 whitespace-nowrap">
                                    <span class="text-[10px] sm:text-sm font-mono text-[#8b7e76]">{{ $student->id }}</span>
                                </td>

                                <td class="px-3 sm:px-4 py-2.5 sm:py-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="avatar-badge overflow-hidden" style="{{ $student->profile_picture ? 'background:none;padding:0;' : '' }}">
                                            @if($student->profile_picture)
                                                <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($student->user->first_name, 0, 1)) }}{{ strtoupper(substr($student->user->last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate">
                                                {{ $student->user->first_name }} {{ $student->user->last_name }}
                                            </div>
                                            <div class="text-[10px] sm:text-[11px] text-[#8b7e76] font-mono break-all truncate max-w-[100px] sm:max-w-xs">
                                                {{ $student->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 whitespace-nowrap">
                                    <span class="student-id-pill text-[10px] sm:text-xs">{{ $student->student_id }}</span>
                                </td>

                                <td class="px-3 sm:px-4 py-2.5 sm:py-3">
                                    @php
                                        $headCounselor = isset($collegeCounselors) ? ($collegeCounselors[$student->college_id] ?? null) : null;
                                    @endphp
                                    <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[140px]">{{ $student->college->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] sm:text-[11px] text-[#8b7e76] mt-0.5 inline-flex items-center">
                                        <i class="fas fa-user-doctor text-[#c4b8b1] text-[9px] sm:text-[10px] mr-1.5"></i>
                                        <span class="truncate max-w-[120px]">{{ $headCounselor ? ($headCounselor->user->first_name . ' ' . $headCounselor->user->last_name) : 'N/A' }}</span>
                                    </div>
                                </td>

                                <td class="px-3 sm:px-4 py-2.5 sm:py-3">
                                    <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[140px]">{{ $student->course }}</div>
                                    <div class="text-[10px] sm:text-[11px] text-[#8b7e76] mt-0.5 inline-flex items-center">
                                        <i class="fas fa-calendar-days-days text-[#c4b8b1] text-[9px] sm:text-[10px] mr-1.5"></i>
                                        Year {{ $student->year_level }}
                                    </div>
                                </td>

                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-[#6b5e57]">
                                        <span class="mini-icon bg-[#fdf2f2] text-[#7a2a2a]/50">
                                            <i class="fas fa-clock text-[9px] sm:text-[10px]"></i>
                                        </span>
                                        {{ $student->lastSessionNote?->session_date?->format('M j, Y') ?? 'N/A' }}
                                    </div>
                                </td>

                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 whitespace-nowrap">
                                    <div class="flex flex-col gap-1.5 items-start">
                                        @if($student->calculated_high_risk)
                                            @if(in_array('assessment', $student->high_risk_reasons))
                                            <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 text-[10px] sm:text-xs font-semibold rounded-full bg-[#fff7ed] text-[#9a3412] border border-[#fed7aa]">
                                                <i class="fas fa-notes-medical mr-1 text-[9px] sm:text-[10px]"></i> Assessment Risk
                                            </span>
                                            @endif
                                            @if(in_array('flagged', $student->high_risk_reasons))
                                            <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 text-[10px] sm:text-xs font-semibold rounded-full bg-[#fef3c7] text-[#92400e] border border-[#fde68a]">
                                                <i class="fas fa-flag mr-1 text-[9px] sm:text-[10px]"></i> Flagged
                                            </span>
                                            @endif
                                        @endif
                                        <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 text-[10px] sm:text-xs font-semibold rounded-full 
                                            {{ $student->student_status == 'new' ? 'bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30' : 
                                               ($student->student_status == 'transferee' ? 'bg-[#eff6ff] text-[#0284c7] border border-[#0ea5e9]/30' : 
                                               ($student->student_status == 'returnee' ? 'bg-[#fffbeb] text-[#b45309] border border-[#f59e0b]/30' : 
                                               'bg-[#f5f0eb] text-[#6b5e57] border border-[#e5e0db]/70')) }}">
                                            <i class="fas {{ $student->student_status == 'new' ? 'fa-star' : ($student->student_status == 'transferee' ? 'fa-arrow-right-arrow-left' : ($student->student_status == 'returnee' ? 'fa-undo' : 'fa-user')) }} mr-1 text-[9px] sm:text-[10px]"></i>
                                            {{ ucfirst($student->student_status ?? 'new') }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.students.edit', $student) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] sm:text-xs font-semibold text-[#7a2a2a] bg-[rgba(122,42,42,0.07)] hover:bg-[rgba(122,42,42,0.14)] transition-colors"
                                           title="Edit Profile">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 sm:py-10 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fas fa-user-xmark text-[#a89f97] text-lg"></i>
                                        </div>
                                        <p class="text-xs sm:text-sm font-medium text-[#6b5e57]">No students found.</p>
                                        <p class="text-[10px] sm:text-xs text-[#8b7e76] mt-1">Try adjusting your search or filter criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                {{ $students->appends(request()->query())->links('vendor.pagination.counselor-resources') }}
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --maroon-900: #3a0c0c;
        --maroon-800: #5c1a1a;
        --maroon-700: #7a2a2a;
        --gold-400: #d4af37;
        --bg-warm: #faf8f5;
        --border-soft: #e5e0db;
        --text-primary: #2c2420;
        --text-secondary: #6b5e57;
        --text-muted: #8b7e76;
    }

    .students-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
    }

    .students-glow {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        pointer-events: none;
        opacity: 0.25;
    }

    .glow-one {
        top: -40px;
        left: -50px;
        width: 220px;
        height: 220px;
        background: var(--gold-400);
    }

    .glow-two {
        bottom: -40px;
        right: -80px;
        width: 260px;
        height: 260px;
        background: var(--maroon-800);
    }

    .hero-card, .panel-card {
        position: relative;
        overflow: hidden;
        border-radius: 0.75rem;
        border: 1px solid var(--border-soft);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 8px rgba(44, 36, 32, 0.04);
        transition: box-shadow 0.2s ease;
    }

    .hero-card:hover, .panel-card:hover {
        box-shadow: 0 4px 12px rgba(44, 36, 32, 0.06);
    }

    .hero-card-pattern {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at top left, rgba(212,175,55,0.08), transparent 35%),
            radial-gradient(circle at bottom right, rgba(92,26,26,0.06), transparent 40%);
        pointer-events: none;
    }

    .hero-icon {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
        flex-shrink: 0;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3);
        background: rgba(254,249,231,0.8);
        padding: 0.2rem 0.55rem;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: var(--maroon-700);
    }

    .hero-badge-dot {
        width: 0.3rem;
        height: 0.3rem;
        border-radius: 999px;
        background: var(--gold-400);
    }

    .summary-card {
        position: relative;
        overflow: hidden;
        border-radius: 0.75rem;
        border: 1px solid rgba(92,26,26,0.15);
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
        min-width: 200px;
    }

    .summary-card-pattern {
        position: absolute;
        inset: 0;
        opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%);
    }

    .summary-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fef9e7;
        flex-shrink: 0;
    }

    .summary-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: rgba(255,255,255,0.7);
    }

    .summary-value {
        font-size: 1.5rem;
        line-height: 1;
        font-weight: 800;
        margin-top: 0.35rem;
    }

    .summary-subtext {
        font-size: 0.7rem;
        color: rgba(255,255,255,0.8);
        margin-top: 0.25rem;
    }

    .panel-topline {
        position: absolute;
        inset-inline: 0;
        top: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%);
    }

    .panel-header {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border-soft)/60;
    }

    .panel-header-icon, .table-header-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .panel-header-icon {
        background: rgba(254,249,231,0.7);
        color: var(--maroon-700);
    }

    .panel-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .panel-subtitle {
        font-size: 0.68rem;
        color: var(--text-muted);
        margin-top: 0.1rem;
    }

    .college-stat-card {
        display: block;
        padding: 1rem;
        border-radius: 0.85rem;
        border: 1px solid var(--border-soft);
        background: rgba(255,255,255,0.98);
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(44,36,32,0.03);
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }
    .college-stat-card:hover {
        border-color: rgba(122,42,42,0.25);
        background: rgba(254,249,231,0.6);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(92,26,26,0.08);
    }
    .college-stat-active {
        border-color: rgba(122,42,42,0.5) !important;
        background: linear-gradient(135deg, rgba(254,249,231,0.8), rgba(255,255,255,1)) !important;
        box-shadow: 0 4px 14px rgba(92,26,26,0.1) !important;
    }
    .college-stat-active::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 0.85rem;
        border: 2px solid var(--maroon-700);
        pointer-events: none;
    }
    .college-stat-count {
        font-size: 1.6rem;
        font-weight: 800;
        color: #2c2420;
        line-height: 1;
    }
    .college-stat-name {
        font-size: 0.65rem;
        font-weight: 700;
        color: #8b7e76;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        line-height: 1.3;
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .stats-icon { 
        width: 2.25rem; height: 2.25rem; border-radius: 0.6rem; 
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .primary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
        text-decoration: none;
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); color: #fef9e7; }

    .input-field {
        border: 1px solid var(--border-soft);
        border-radius: 0.6rem;
        background: rgba(255,255,255,0.9);
        color: var(--text-primary);
        outline: none;
        transition: all 0.2s ease;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }

    .input-field:focus {
        border-color: var(--maroon-700);
        box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }

    .search-btn {
        border-radius: 0.6rem;
        color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
        transition: all 0.2s ease;
    }

    .search-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(92,26,26,0.2);
    }

    .table-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.6rem;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid var(--border-soft)/60;
        background: rgba(250,248,245,0.4);
    }

    .table-header-icon {
        background: rgba(254,249,231,0.6);
    }

    .table-live-pill {
        display: inline-flex;
        align-items: center;
        font-size: 0.65rem;
        color: var(--text-secondary);
        background: rgba(250,248,245,0.6);
        border: 1px solid var(--border-soft);
        padding: 0.25rem 0.5rem;
        border-radius: 999px;
        font-weight: 500;
    }

    .table-row {
        transition: background-color 0.15s ease;
    }

    .table-row:hover {
        background: rgba(254,249,231,0.35);
    }

    .avatar-badge {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7a2a2a;
        font-weight: 700;
        font-size: 0.7rem;
        background: linear-gradient(135deg, #fef9e7 0%, #f5e6b8 100%);
        border: 1px solid rgba(212,175,55,0.3);
        flex-shrink: 0;
    }

    .student-id-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 0.55rem;
        background: rgba(245,240,235,0.7);
        border: 1px solid var(--border-soft)/60;
        color: var(--text-secondary);
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 600;
    }

    .mini-icon {
        width: 1.4rem;
        height: 1.4rem;
        border-radius: 0.45rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .action-link {
        color: var(--text-secondary);
        transition: all 0.18s ease;
    }

    .action-link:hover {
        color: var(--maroon-700);
        transform: translateY(-1px);
    }

    .empty-state-icon {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(245,240,235,0.6);
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.04);
    }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .table-header-bar { padding: 0.65rem 1rem; }
        .avatar-badge { width: 2rem; height: 2rem; font-size: 0.65rem; }
    }
</style>

@endsection