@extends('layouts.app')
@section('title', 'Students - Counselor')
@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c; --maroon-800: #5c1a1a; --maroon-700: #7a2a2a;
        --gold-500: #c9a227; --gold-400: #d4af37;
        --bg-warm: #faf8f5; --border-soft: #e5e0db;
        --text-primary: #2c2420; --text-secondary: #6b5e57; --text-muted: #8b7e76;
    }
    .stu-shell { position:relative; overflow:hidden; background:var(--bg-warm); min-height:100vh; padding-bottom:2rem; }
    .stu-glow { position:absolute; border-radius:50%; filter:blur(80px); pointer-events:none; opacity:0.25; }
    .stu-glow.one { top:-30px; left:-40px; width:200px; height:200px; background:var(--gold-400); }
    .stu-glow.two { bottom:-30px; right:-60px; width:220px; height:220px; background:var(--maroon-800); }

    .hero-card, .panel-card {
        position:relative; overflow:hidden; border-radius:0.75rem;
        border:1px solid var(--border-soft); background:rgba(255,255,255,0.95);
        backdrop-filter:blur(8px); box-shadow:0 2px 8px rgba(44,36,32,0.04);
        transition:box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover { box-shadow:0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .panel-card::before {
        content:""; position:absolute; inset:0; pointer-events:none;
        background:radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }
    .hero-icon {
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
        width:2.75rem; height:2.75rem; border-radius:0.75rem; color:#fef9e7;
        background:linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow:0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-badge {
        display:inline-flex; align-items:center; gap:0.4rem; border-radius:999px;
        border:1px solid rgba(212,175,55,0.3); background:rgba(254,249,231,0.8);
        padding:0.2rem 0.55rem; font-size:9px; font-weight:700; text-transform:uppercase;
        letter-spacing:0.16em; color:var(--maroon-700);
    }
    .hero-badge-dot { width:0.3rem; height:0.3rem; border-radius:999px; background:var(--gold-400); }
    .panel-topline { position:absolute; inset-inline:0; top:0; height:3px; background:linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }

    .field-label { display:block; font-size:0.65rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.35rem; text-transform:uppercase; letter-spacing:0.08em; }
    .input-field, .select-field {
        width:100%; border:1px solid var(--border-soft); border-radius:0.6rem;
        background:rgba(255,255,255,0.9); color:var(--text-primary); outline:none;
        transition:all 0.2s ease; font-size:0.8rem; padding:0.55rem 0.75rem;
    }
    .input-field:focus, .select-field:focus { border-color:var(--maroon-700); box-shadow:0 0 0 3px rgba(92,26,26,0.08); }

    .primary-btn {
        border-radius:0.6rem; font-weight:600; transition:all 0.2s ease;
        display:inline-flex; align-items:center; justify-content:center; white-space:nowrap;
        color:#fef9e7; background:linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow:0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform:translateY(-1px); box-shadow:0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        border-radius:0.6rem; font-weight:600; transition:all 0.2s ease;
        display:inline-flex; align-items:center; justify-content:center; white-space:nowrap;
        color:var(--text-secondary); background:rgba(255,255,255,0.9); border:1px solid var(--border-soft);
    }
    .secondary-btn:hover { background:rgba(254,249,231,0.7); border-color:var(--maroon-700); }

    .avatar-badge {
        flex-shrink:0; width:2.25rem; height:2.25rem; border-radius:0.6rem;
        display:flex; align-items:center; justify-content:center;
        background:rgba(254,249,231,0.6); border:1px solid rgba(212,175,55,0.3);
        font-size:0.7rem; font-weight:700; color:var(--maroon-700); overflow:hidden;
    }
    .table-row { transition:background-color 0.15s ease; cursor:pointer; }
    .table-row:hover { background:rgba(254,249,231,0.35); }
    .table-row.high-risk { background:rgba(254,242,242,0.5); }
    .table-row.high-risk:hover { background:rgba(254,226,226,0.6); }

    .empty-state { text-align:center; padding:2.5rem 1rem; color:var(--text-muted); }
    .empty-state-icon {
        width:3.5rem; height:3.5rem; border-radius:1rem;
        display:inline-flex; align-items:center; justify-content:center;
        background:rgba(254,249,231,0.7); color:var(--maroon-700);
        margin-bottom:0.75rem; font-size:1.1rem;
    }
</style>

<div class="min-h-screen stu-shell">
    <div class="stu-glow one"></div>
    <div class="stu-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">

        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex items-start gap-3">
                    <div class="hero-icon">
                        <i class="fas fa-user-graduate text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="hero-badge"><span class="hero-badge-dot"></span>My Caseload</div>
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Students</h1>
                        <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">All students in your assigned college(s).</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="panel-card mb-5 sm:mb-6">
            <div class="panel-topline"></div>
            <form method="GET" class="p-4 sm:p-5">
                <div class="flex flex-wrap items-end gap-3">
                    <div class="flex-1 min-w-[180px]">
                        <label class="field-label">Search</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[#a89f97] text-xs"></i>
                            <input type="text" name="search" value="{{ $search }}"
                                   placeholder="‎ ‎ ‎ ‎ Student ID, name, email, or course..."
                                   class="input-field pl-9">
                        </div>
                    </div>
                    <div class="w-44 sm:w-52">
                        <label class="field-label">College</label>
                        <select name="college" class="select-field">
                            <option value="" {{ empty($college) ? 'selected' : '' }}>All Assigned Colleges</option>
                            @foreach($colleges as $c)
                                <option value="{{ $c->id }}" {{ (string)($college ?? '') === (string)$c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2 pb-0.5">
                        <a href="{{ route('counselor.students.index') }}" class="secondary-btn px-3 py-2 text-xs sm:text-sm">
                            <i class="fas fa-rotate-left mr-1 text-[9px]"></i>Reset
                        </a>
                        <button type="submit" class="primary-btn px-3 py-2 text-xs sm:text-sm">
                            <i class="fas fa-magnifying-glass mr-1 text-[9px]"></i>Search
                        </button>
                    </div>
                </div>
                @if($students->total() > 0)
                <div class="mt-3 text-[10px] sm:text-xs text-[#8b7e76]">
                    Showing {{ $students->firstItem() }}–{{ $students->lastItem() }} of {{ $students->total() }} students
                </div>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="panel-card overflow-hidden">
            @if($students->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-user-slash"></i></div>
                    <p class="text-sm font-medium text-[#2c2420]">No students found.</p>
                    <p class="text-xs text-[#8b7e76] mt-1">Try adjusting your search or college filter.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px]">
                        <thead class="bg-[#faf8f5]/80">
                            <tr>
                                <th class="px-4 sm:px-5 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Student</th>
                                <th class="px-4 sm:px-5 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Student ID</th>
                                <th class="px-4 sm:px-5 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">College</th>
                                <th class="px-4 sm:px-5 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Course / Year</th>
                                <th class="px-4 sm:px-5 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Last Session</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                            @foreach($students as $student)
                            @php
                                $stressResponses = $student->needsAssessment?->stress_responses ?? [];
                                $stressResponses = is_array($stressResponses) ? $stressResponses : [];
                                $riskResponses = ['Hurt myself', 'Attempted to end my life', 'Thought it would be better dead'];
                                $hasSelfHarmRisk = !$student->high_risk_overridden
                                    && count(array_intersect($riskResponses, $stressResponses)) > 0;
                                $isHighRisk = $student->is_high_risk || $hasSelfHarmRisk;
                            @endphp
                            <tr class="table-row {{ $isHighRisk ? 'high-risk' : '' }}"
                                onclick="window.location='{{ route('counselor.students.profile', $student) }}'">
                                <td class="px-4 sm:px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="avatar-badge {{ $isHighRisk ? 'ring-2 ring-red-400' : '' }}">
                                            @if($student->profile_picture)
                                                <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($student->user->first_name, 0, 1)) }}{{ strtoupper(substr($student->user->last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[180px]">
                                                {{ $student->user->first_name }} {{ $student->user->last_name }}
                                            </div>
                                            <div class="text-[10px] sm:text-xs text-[#8b7e76] font-mono truncate max-w-[180px]">
                                                {{ $student->user->email }}
                                            </div>
                                            @if($isHighRisk)
                                            <div class="flex flex-wrap gap-1 mt-0.5">
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-red-100 text-red-700 px-1.5 py-0.5 rounded-full border border-red-200">
                                                    <i class="fas fa-exclamation-triangle text-[8px]"></i> High-risk
                                                </span>
                                                @if($student->is_high_risk)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-orange-100 text-orange-700 px-1.5 py-0.5 rounded-full border border-orange-200">
                                                    <i class="fas fa-flag text-[8px]"></i> Flagged
                                                </span>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                    <span class="text-xs font-mono text-[#6b5e57]">{{ $student->student_id }}</span>
                                </td>
                                <td class="px-4 sm:px-5 py-3.5">
                                    <span class="text-xs sm:text-sm text-[#2c2420] truncate block max-w-[200px]">{{ $student->college->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-4 sm:px-5 py-3.5">
                                    <div class="text-xs sm:text-sm text-[#2c2420] truncate max-w-[180px]">{{ $student->course }}</div>
                                    <div class="text-[10px] sm:text-xs text-[#8b7e76]">Year {{ $student->year_level }}</div>
                                </td>
                                <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                    <span class="text-xs text-[#6b5e57]">
                                        {{ $student->lastSessionNote?->session_date?->format('M j, Y') ?? '—' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($students->hasPages())
                <div class="px-4 sm:px-5 py-3 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                    {{ $students->links() }}
                </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection
