@extends('layouts.app')

@section('title', 'Book Appointment - OGC')

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

    .book-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 2rem;
    }
    .book-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .book-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .book-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover { 
        box-shadow: 0 4px 14px rgba(44,36,32,0.06); 
    }
    .hero-card::before, .panel-card::before, .glass-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }

    .summary-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid rgba(92,26,26,0.15);
        background: linear-gradient(135deg, #5c1a1a 0%, #3a0c0c 100%); color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, #d4af37, transparent 40%);
        pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.8);
        padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.16em; color: var(--maroon-700);
    }
    .hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: var(--gold-400); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { 
        width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; 
        align-items: center; justify-content: center; 
        background: rgba(254,249,231,0.7); color: var(--maroon-700); 
    }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { 
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); 
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; 
    }
    .input-field, .select-field, .textarea-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus, .textarea-field:focus { 
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); 
    }
    .textarea-field { min-height: 120px; resize: vertical; line-height: 1.5; }

    .error-text {
        font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem;
        display: flex; align-items: center; gap: 0.25rem;
    }
    .error-text::before { content: "•"; font-weight: bold; }

    .helper-text {
        font-size: 0.7rem; color: var(--text-muted); margin-top: 0.25rem;
    }

    .calendar-card {
        position: relative; border: 1px solid var(--border-soft); border-radius: 0.75rem;
        background: rgba(255,255,255,0.95); padding: 1rem; overflow: hidden;
    }
    .calendar-nav {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.5rem 0; margin-bottom: 0.5rem;
    }
    .calendar-nav-btn {
        width: 2.25rem; height: 2.25rem; border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--border-soft); color: var(--text-secondary);
        transition: all 0.18s ease; font-size: 1rem;
    }
    .calendar-nav-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); color: var(--maroon-700); }
    .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.5rem; }
    .calendar-day-header {
        text-align: center; font-size: 0.65rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);
    }
    .calendar-day {
        width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 500; border: 1px solid transparent;
        transition: all 0.18s ease;
    }
    .calendar-day.available {
        border-color: rgba(122,42,42,0.3); color: var(--maroon-700);
        background: rgba(254,249,231,0.5);
    }
    .calendar-day.available:hover {
        background: rgba(212,175,55,0.2); border-color: var(--gold-400);
    }
    .calendar-day.selected {
        background: var(--maroon-700); color: #fef9e7; border-color: var(--maroon-700);
    }
    .calendar-day:disabled {
        color: var(--text-muted); cursor: not-allowed; opacity: 0.5;
    }
    .calendar-status {
        font-size: 0.7rem; color: var(--text-muted); margin-top: 0.5rem;
        min-height: 1rem;
    }
    .calendar-status.success { color: #065f46; }
    .calendar-status.error { color: #b91c1c; }

    .time-slot {
        padding: 0.75rem; border-radius: 0.5rem; border: 1px solid var(--border-soft);
        text-align: center; font-size: 0.75rem; font-weight: 500;
        transition: all 0.18s ease; cursor: pointer;
        background: rgba(255,255,255,0.9);
    }
    .time-slot:hover { border-color: var(--maroon-700); background: rgba(254,249,231,0.6); }
    .time-slot.selected {
        border-color: var(--maroon-700); background: rgba(254,249,231,0.9);
        color: var(--maroon-700); font-weight: 600;
    }

    .form-actions {
        display: flex; flex-direction: column-reverse; gap: 0.75rem;
        padding-top: 1rem; border-top: 1px solid var(--border-soft)/60;
        position: sticky;
        bottom: 0;
        background: rgba(255,255,255,0.98);
        backdrop-filter: blur(8px);
        z-index: 10;
        padding-bottom: 1rem;
    }
    @media (min-width: 768px) { 
        .form-actions { flex-direction: row; justify-content: flex-end; } 
    }

    .primary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: var(--text-secondary); background: rgba(255,255,255,0.9);
        border: 1px solid var(--border-soft);
    }
    .secondary-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .input-field, .select-field, .textarea-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; }
        .calendar-day { width: 2rem; height: 2rem; font-size: 0.7rem; }
        .time-slot { padding: 0.5rem; font-size: 0.7rem; }
        .calendar-nav-btn { width: 2rem; height: 2rem; font-size: 0.9rem; }
    }
</style>

<div class="min-h-screen book-shell">
    <div class="book-glow one"></div>

<div id="createManualOverrideModal" class="fixed inset-0 z-[2000] hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/40" onclick="closeCreateOverrideModal()"></div>
    <div class="relative mx-auto w-[min(560px,92vw)] mt-24 sm:mt-28">
        <div class="glass-card p-5 sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-[#2c2420] uppercase tracking-wide">Override Availability</h3>
                    <p class="text-xs text-[var(--text-muted)] mt-1">Set date and time manually (optional). Calendar selection will not be overwritten unless you apply.</p>
                </div>
                <button type="button" class="secondary-btn px-3 py-2 text-xs" onclick="closeCreateOverrideModal()">Close</button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">
                <div>
                    <label class="field-label">Date</label>
                    <input type="date" name="manual_date" id="createManualDate" class="input-field text-xs sm:text-sm bg-white">
                </div>
                <div>
                    <label class="field-label">Time</label>
                    <input type="time" name="manual_time" id="createManualTime" class="input-field text-xs sm:text-sm bg-white">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-end mt-6">
                <button type="button" class="secondary-btn px-5 py-2.5 text-sm" onclick="clearCreateManualOverride()">Clear</button>
                <button type="button" class="primary-btn px-5 py-2.5 text-sm" onclick="applyCreateManualOverride()">
                    <i class="fas fa-check mr-2"></i>Apply
                </button>
            </div>
        </div>
    </div>
</div>
    <div class="book-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header Section -->
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Left: Hero Card -->
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-plus text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                New Session
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Book Appointment</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Schedule a new session directly for a student.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Summary Card -->
            <div class="summary-card">
                <div class="relative h-full flex flex-row items-center justify-between gap-4 p-4 sm:p-5">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="summary-icon flex-shrink-0">
                            <i class="fas fa-calendar-plus text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="summary-label">Quick Actions</p>
                            <p class="summary-value">Book Session</p>
                            <p class="summary-subtext">Auto-approved upon booking by counselor.</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 flex-shrink-0">
                        <a href="{{ route('counselor.appointments') }}"
                           class="primary-btn px-4 py-2 text-xs sm:text-sm whitespace-nowrap" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); box-shadow: none;">
                            <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>All Appointments</span>
                        </a>
                        <a href="{{ route('counselor.calendar') }}"
                           class="primary-btn px-4 py-2 text-xs sm:text-sm whitespace-nowrap" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); box-shadow: none;">
                            <i class="fas fa-calendar-days mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>View Calendar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <form id="appointmentForm" action="{{ route('counselor.appointments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="counselor_id" id="counselorIdInput" value="{{ $selectedCounselor->id }}">

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 lg:gap-6 items-start">
                
                <!-- Left Column: Booking Details -->
                <div class="xl:col-span-7 flex flex-col gap-5 lg:gap-6">
                    
                    <!-- Student Info Panel -->
                    <div class="panel-card">
                        <div class="panel-topline"></div>
                        <div class="p-4 sm:p-5 md:p-6">
                            <h2 class="text-sm font-bold text-[#2c2420] uppercase tracking-wide mb-4 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[rgba(212,175,55,0.15)] text-[var(--gold-500)] flex items-center justify-center">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                Student Selection
                            </h2>
                            <div class="grid grid-cols-1 gap-4 sm:gap-5">
                                @if($counselorAssignments->count() > 1)
                                <div>
                                    <label class="field-label">College</label>
                                    <select id="collegeSelect" class="select-field text-xs sm:text-sm" required>
                                        @foreach($counselorAssignments as $assignment)
                                            <option value="{{ $assignment->id }}" {{ (int) $selectedCounselor->id === (int) $assignment->id ? 'selected' : '' }}>
                                                {{ $assignment->college->name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <div>
                                    <label class="field-label">Student</label>
                                    <select name="student_id" id="studentSelect" class="select-field text-xs sm:text-sm" required>
                                        <option value="">Choose a student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->student_id }} - {{ $student->user->first_name }} {{ $student->user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Session Details Panel -->
                    <div class="panel-card">
                        <div class="panel-topline"></div>
                        <div class="p-4 sm:p-5 md:p-6">
                            <h2 class="text-sm font-bold text-[#2c2420] uppercase tracking-wide mb-4 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[rgba(212,175,55,0.15)] text-[var(--gold-500)] flex items-center justify-center">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                Session Details
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="field-label">Type of Booking</label>
                                    <select name="booking_type" id="bookingType" class="select-field text-xs sm:text-sm" required>
                                        <option value="">Choose a booking type</option>
                                        <option value="Initial Interview" {{ old('booking_type') === 'Initial Interview' ? 'selected' : '' }}>Initial Interview</option>
                                        <option value="Counseling" {{ old('booking_type') === 'Counseling' ? 'selected' : '' }}>Counseling</option>
                                        <option value="Consultation" {{ old('booking_type') === 'Consultation' ? 'selected' : '' }}>Consultation</option>
                                    </select>
                                    <p class="helper-text" id="bookingTypeHelp">Select the reason for the appointment.</p>
                                    @error('booking_type')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-1">
                                    <label class="field-label">Booking Category</label>
                                    <select name="booking_category" id="bookingCategory" class="select-field text-xs sm:text-sm" required>
                                        <option value="walk-in" {{ old('booking_category') === 'walk-in' ? 'selected' : '' }}>Walk-in</option>
                                        <option value="referred" {{ old('booking_category') === 'referred' ? 'selected' : '' }}>Referred</option>
                                        <option value="called-in" {{ old('booking_category') === 'called-in' ? 'selected' : '' }}>Called-in</option>
                                    </select>
                                    <p class="helper-text">How it was initiated.</p>
                                    @error('booking_category')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-1 {{ old('booking_category') === 'referred' ? '' : 'hidden' }}" id="referredByWrap">
                                    <label class="field-label">Referred by</label>
                                    <input type="text" name="referred_by" id="referredByInput" value="{{ old('referred_by') }}"
                                           class="input-field text-xs sm:text-sm" maxlength="255"
                                           placeholder="e.g. Teacher, Parent">
                                    @error('referred_by')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label class="field-label">Concern / Agenda</label>
                                    <textarea name="concern" rows="3" class="textarea-field" required placeholder="Briefly describe the concern or agenda for this session...">{{ old('concern') }}</textarea>
                                    @error('concern')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Scheduling & Actions -->
                <div class="xl:col-span-5 flex flex-col gap-5 lg:gap-6">
                    <div class="panel-card sticky top-6">
                        <div class="panel-topline"></div>
                        <div class="p-4 sm:p-5 md:p-6">
                            <div class="rounded-xl border p-4 sm:p-5 mb-5 transition-all duration-200" style="border-color: rgba(212,175,55,0.4); background: rgba(254,249,231,0.4);" onmouseover="this.style.background='rgba(254,249,231,0.8)';" onmouseout="this.style.background='rgba(254,249,231,0.4)';">
                                <label class="flex items-center gap-3 cursor-pointer select-none">
                                    <input type="checkbox" name="override_availability" id="createOverrideCheck" value="1"
                                           onchange="toggleCreateOverride(this.checked)"
                                           style="width:1.25rem;height:1.25rem;accent-color:var(--gold-500);cursor:pointer;">
                                    <span class="text-sm font-bold uppercase tracking-wide" style="color:var(--text-primary);">
                                        <i class="fas fa-bolt mr-1.5 text-[var(--gold-500)]"></i> Override Availability
                                    </span>
                                </label>
                                <p class="text-xs text-[var(--text-muted)] mt-1 ml-8">Book outside set hours or daily limit.</p>
                            </div>

                            <h2 class="text-sm font-bold text-[#2c2420] uppercase tracking-wide mb-4 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[rgba(212,175,55,0.15)] text-[var(--gold-500)] flex items-center justify-center">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                Schedule Date & Time
                            </h2>

                            <div>
                                <label class="field-label">Select Date</label>
                                <div class="calendar-card shadow-sm border-[var(--border-soft)]">
                                    <!-- Loading Overlay -->
                                    <div id="calendarLoading" class="absolute inset-0 z-10 bg-white/80 backdrop-blur-[2px] flex flex-col items-center justify-center transition-opacity duration-200 hidden">
                                        <div class="bg-white px-4 py-3 rounded-xl shadow-sm border border-[#e5e0db]/80 flex items-center gap-3">
                                            <i class="fas fa-circle-notch fa-spin text-lg text-[#7a2a2a]"></i>
                                            <span class="text-xs font-bold text-[#5c1a1a] tracking-wide ">Loading Dates...</span>
                                        </div>
                                    </div>

                                    <div class="calendar-nav">
                                        <button type="button" id="calendarPrev" class="calendar-nav-btn">‹</button>
                                        <h3 id="calendarMonthLabel" class="text-sm font-semibold text-[#2c2420]"></h3>
                                        <button type="button" id="calendarNext" class="calendar-nav-btn">›</button>
                                    </div>
                                    <div class="calendar-grid mb-2">
                                        <span class="calendar-day-header">Sun</span>
                                        <span class="calendar-day-header">Mon</span>
                                        <span class="calendar-day-header">Tue</span>
                                        <span class="calendar-day-header">Wed</span>
                                        <span class="calendar-day-header">Thu</span>
                                        <span class="calendar-day-header">Fri</span>
                                        <span class="calendar-day-header">Sat</span>
                                    </div>
                                    <div id="calendarGrid" class="calendar-grid"></div>
                                    <p id="calendarStatus" class="calendar-status mt-3 text-center">Loading available dates...</p>
                                </div>
                                <input type="hidden" name="appointment_date" id="dateSelect" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('appointment_date')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-5" id="createSlotWrap">
                                <label class="field-label">Available Time Slots</label>
                                <div id="timeSlots" class="grid grid-cols-2 gap-2 sm:gap-3">
                                    <div class="col-span-2 text-[#8b7e76] text-center p-4 border-2 border-dashed border-[#e5e0db] rounded-lg text-xs">
                                        Select a date to see available time slots
                                    </div>
                                </div>
                                <input type="hidden" name="start_time" id="selectedTime" required>
                                @error('start_time')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Options Panel (Full Width) -->
            <div class="panel-card mt-5 lg:mt-6">
                <div class="panel-topline"></div>
                <div class="p-4 sm:p-5 md:p-6">
                    <h2 class="text-sm font-bold text-[#2c2420] uppercase tracking-wide mb-4 flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[rgba(212,175,55,0.15)] text-[var(--gold-500)] flex items-center justify-center">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        Special Options
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                        <!-- Risk Assessment -->
                        <div class="h-full rounded-xl border p-4 sm:p-5 transition-all duration-200" style="border-color: rgba(220,38,38,0.25); background: rgba(254,242,242,0.4);" onmouseover="this.style.background='rgba(254,242,242,0.8)';" onmouseout="this.style.background='rgba(254,242,242,0.4)';">
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <input type="checkbox" name="flag_high_risk" id="flagHighRisk" value="1"
                                       class="w-5 h-5 rounded accent-red-600 cursor-pointer"
                                       {{ old('flag_high_risk') ? 'checked' : '' }}
                                       onchange="document.getElementById('highRiskNotesWrap').classList.toggle('hidden', !this.checked)">
                                <span class="text-sm font-bold uppercase tracking-wide" style="color:#991b1b;">
                                    <i class="fas fa-exclamation-triangle mr-1.5 text-red-600"></i> Flag this student as High-Risk
                                </span>
                            </label>
                            <p class="text-xs text-[var(--text-muted)] mt-1 ml-8">Mark this student for priority monitoring.</p>

                            <div id="highRiskNotesWrap" class="mt-4 pt-4 border-t border-[rgba(220,38,38,0.15)] {{ old('flag_high_risk') ? '' : 'hidden' }}">
                                <label class="field-label" style="color:#7f1d1d;">Reason for High-Risk Flag</label>
                                <textarea name="high_risk_notes" rows="2" class="textarea-field w-full mt-2"
                                          placeholder="Briefly describe the reason..."
                                          style="border-color:rgba(220,38,38,0.3); background: white;">{{ old('high_risk_notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="panel-card mt-5 lg:mt-6 bg-[rgba(255,255,255,0.95)] backdrop-blur-md border-t-0">
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('counselor.appointments') }}" class="secondary-btn px-6 py-2.5 text-sm w-full sm:w-auto text-center">Cancel</a>
                    <button type="submit" class="primary-btn px-6 py-2.5 text-sm w-full sm:w-auto text-center flex justify-center items-center gap-2">
                        <i class="fas fa-check-circle"></i> Book Now (Auto-Approved)
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const counselorIdInput = document.getElementById('counselorIdInput');
    const collegeSelect = document.getElementById('collegeSelect');
    const studentSelect = document.getElementById('studentSelect');
    const bookingTypeSelect = document.getElementById('bookingType');
    const bookingTypeInitial = bookingTypeSelect?.querySelector('option[value="Initial Interview"]');
    const bookingTypeHelp = document.getElementById('bookingTypeHelp');
    const bookingCategorySelect = document.getElementById('bookingCategory');
    const referredByWrap = document.getElementById('referredByWrap');
    const referredByInput = document.getElementById('referredByInput');
    const dateSelect = document.getElementById('dateSelect');
    const calendarGrid = document.getElementById('calendarGrid');
    const calendarMonthLabel = document.getElementById('calendarMonthLabel');
    const calendarPrev = document.getElementById('calendarPrev');
    const calendarNext = document.getElementById('calendarNext');
    const calendarStatus = document.getElementById('calendarStatus');
    const timeSlots = document.getElementById('timeSlots');
    const selectedTime = document.getElementById('selectedTime');
    const overrideCheck = document.getElementById('createOverrideCheck');

    function updateReferredByVisibility() {
        if (!bookingCategorySelect || !referredByWrap) return;
        const show = String(bookingCategorySelect.value) === 'referred';
        referredByWrap.classList.toggle('hidden', !show);
    }

    if (bookingCategorySelect) {
        bookingCategorySelect.addEventListener('change', updateReferredByVisibility);
    }

    let currentSelectedSlot = null;
    const minDate = new Date();
    minDate.setHours(0, 0, 0, 0);
    // Counselors can book same-day appointments

    updateReferredByVisibility();

    let currentMonth = new Date(minDate.getFullYear(), minDate.getMonth(), 1);
    let selectedDate = null;
    let availabilityByDate = new Map();
    let availabilityRequestId = 0;

    const students = {!! json_encode($students->map(function($s) {
        return [
            'id' => $s->id,
            'student_id' => $s->student_id,
            'first_name' => optional($s->user)->first_name,
            'last_name' => optional($s->user)->last_name,
            'college_id' => $s->college_id,
            'year_level' => $s->year_level,
            'initial_interview_completed' => $s->initial_interview_completed,
        ];
    })->values()) !!};

    const initialInterviewBookedStudentIds = {!! json_encode($initialInterviewBookedStudentIds ?? []) !!};
    const initialInterviewInProgressStudentIds = {!! json_encode($initialInterviewInProgressStudentIds ?? []) !!};

    const counselorAssignments = {!! json_encode($counselorAssignments->map(function($a) {
        return [
            'id' => $a->id,
            'college_id' => $a->college_id,
        ];
    })->values()) !!};

    function getSelectedAssignmentId() {
        return (collegeSelect && collegeSelect.value) ? collegeSelect.value : counselorIdInput.value;
    }

    function getSelectedCollegeIdForAssignment(assignmentId) {
        const assignment = counselorAssignments.find(a => String(a.id) === String(assignmentId));
        return assignment ? assignment.college_id : null;
    }

    function populateStudentsForAssignment(assignmentId) {
        if (!studentSelect) return;

        const selectedCollegeId = getSelectedCollegeIdForAssignment(assignmentId);
        const currentSelectedStudentId = studentSelect.value;

        studentSelect.innerHTML = '<option value="">Choose a student</option>';

        const filtered = selectedCollegeId
            ? students.filter(s => String(s.college_id) === String(selectedCollegeId))
            : students;

        filtered.forEach(s => {
            const option = document.createElement('option');
            option.value = s.id;
            option.textContent = `${s.student_id} - ${s.first_name ?? ''} ${s.last_name ?? ''}`.trim();
            studentSelect.appendChild(option);
        });

        if (currentSelectedStudentId && filtered.some(s => String(s.id) === String(currentSelectedStudentId))) {
            studentSelect.value = currentSelectedStudentId;
        }

        updateBookingTypeOptions();
    }

    function updateBookingTypeOptions() {
        if (!studentSelect || !bookingTypeSelect || !bookingTypeInitial || !bookingTypeHelp) return;

        const selectedStudentId = studentSelect.value;
        const student = students.find(s => String(s.id) === String(selectedStudentId));

        bookingTypeSelect.querySelectorAll('option').forEach(option => {
            option.disabled = false;
            option.hidden = false;
        });

        if (!student) {
            bookingTypeHelp.textContent = 'Select the reason for the appointment.';
            return;
        }

        const hasCompletedInitialInterview = student.initial_interview_completed === true;
        const hasInitialInterviewAppointment = initialInterviewBookedStudentIds
            .map(id => String(id))
            .includes(String(student.id));

        if (hasCompletedInitialInterview || hasInitialInterviewAppointment) {
            if (bookingTypeSelect.value === 'Initial Interview') {
                bookingTypeSelect.value = '';
            }
            bookingTypeInitial.disabled = true;
            bookingTypeInitial.hidden = true;
            bookingTypeHelp.textContent = hasCompletedInitialInterview
                ? 'Initial Interview is already completed.'
                : 'Initial Interview is already booked.';
            return;
        }

        bookingTypeHelp.textContent = 'Select the reason for the appointment.';
    }

    function formatDateValue(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function formatMonthLabel(date) {
        return date.toLocaleString('en-US', { month: 'long', year: 'numeric' });
    }

    function isSameDay(a, b) {
        return a && b &&
            a.getFullYear() === b.getFullYear() &&
            a.getMonth() === b.getMonth() &&
            a.getDate() === b.getDate();
    }

    function setCalendarStatus(message, tone = 'muted') {
        if (!calendarStatus) return;
        calendarStatus.textContent = message;
        calendarStatus.className = 'calendar-status';
        if (tone === 'success') {
            calendarStatus.classList.add('success');
        } else if (tone === 'error') {
            calendarStatus.classList.add('error');
        }
    }

    function renderCalendar() {
        if (!calendarGrid || !calendarMonthLabel) return;

        calendarMonthLabel.textContent = formatMonthLabel(currentMonth);
        calendarGrid.innerHTML = '';

        const counselorId = counselorIdInput.value;
        const firstDayOfMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1);
        const startDay = firstDayOfMonth.getDay();
        const daysInMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 0).getDate();

        for (let i = 0; i < startDay; i++) {
            const spacer = document.createElement('div');
            calendarGrid.appendChild(spacer);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), day);
            const dateValue = formatDateValue(date);
            const isPast = date < minDate;
            const isOverride = overrideCheck && overrideCheck.checked;
            const availabilityKnown = availabilityByDate.has(dateValue);
            const isAvailable = availabilityByDate.get(dateValue) === true;
            const isDisabled = !counselorId || isPast || (!isOverride && (!availabilityKnown || !isAvailable));

            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = day;
            button.disabled = isDisabled;
            button.className = 'calendar-day';

            if (isDisabled) {
                button.classList.add('disabled');
            } else {
                button.classList.add('available');
            }

            if (selectedDate && isSameDay(selectedDate, date)) {
                button.classList.add('selected');
            }

            button.addEventListener('click', () => {
                if (button.disabled) return;
                selectedDate = date;
                dateSelect.value = formatDateValue(date);
                selectedTime.value = '';
                currentSelectedSlot = null;
                setCalendarStatus(`Selected date: ${date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}`, 'success');
                renderCalendar();
                loadAvailableSlots();
            });

            calendarGrid.appendChild(button);
        }
    }

    async function loadMonthAvailability() {
        const counselorId = counselorIdInput.value;
        const calendarLoading = document.getElementById('calendarLoading');
        availabilityByDate = new Map();
        renderCalendar();

        if (!counselorId) {
            setCalendarStatus('Unable to load availability. Please refresh the page.', 'error');
            return;
        }

        const requestId = ++availabilityRequestId;
        setCalendarStatus('Checking available dates...');
        if (calendarLoading) calendarLoading.classList.remove('hidden');

        const monthValue = `${currentMonth.getFullYear()}-${String(currentMonth.getMonth() + 1).padStart(2, '0')}`;
        const isOverride = overrideCheck && overrideCheck.checked;

        try {
            const response = await fetch(`/appointments/available-dates?counselor_id=${counselorId}&month=${monthValue}&allow_today=1&override_availability=${isOverride ? 1 : 0}`);
            if (!response.ok) {
                throw new Error('Failed to load available dates');
            }
            const data = await response.json();
            if (requestId !== availabilityRequestId) return;
            const availability = data.availability || {};
            Object.keys(availability).forEach(dateValue => {
                availabilityByDate.set(dateValue, availability[dateValue] === true);
            });
        } catch (error) {
            if (requestId !== availabilityRequestId) return;
            setCalendarStatus('Unable to load available dates. Please try again.', 'error');
            if (calendarLoading) calendarLoading.classList.add('hidden');
            renderCalendar();
            return;
        }

        if (requestId !== availabilityRequestId) return;
        if (calendarLoading) calendarLoading.classList.add('hidden');

        const hasAnyAvailability = Array.from(availabilityByDate.values()).some(value => value);
        if (!hasAnyAvailability) {
            setCalendarStatus('No available dates for this counselor in the selected month.', 'error');
        } else {
            setCalendarStatus('Available dates are highlighted. Select a date to continue.');
        }

        if (selectedDate && (!availabilityByDate.get(formatDateValue(selectedDate)))) {
            selectedDate = null;
            dateSelect.value = '';
            selectedTime.value = '';
        }

        renderCalendar();
    }

    function loadAvailableSlots() {
        const counselorId = counselorIdInput.value;
        const date = dateSelect.value;
        const isOverride = overrideCheck && overrideCheck.checked;

        if (!counselorId || !date) {
            timeSlots.innerHTML = '<div class="text-[#8b7e76] text-center p-4 border-2 border-dashed border-[#e5e0db] rounded-lg text-xs">Select a counselor and date to see available time slots</div>';
            selectedTime.value = '';
            return;
        }

        timeSlots.innerHTML = '<div class="text-[#8b7e76] text-center p-4 border-2 border-dashed border-[#e5e0db] rounded-lg text-xs">Loading available slots...</div>';

        fetch(`{{ route('appointments.available-slots') }}?counselor_id=${counselorId}&date=${date}&override_availability=${isOverride ? 1 : 0}`)
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    timeSlots.innerHTML = `<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg text-xs">${data.message}</div>`;
                    selectedTime.value = '';
                    return;
                }

                if (!Array.isArray(data.available_slots) || data.available_slots.length === 0) {
                    timeSlots.innerHTML = '<div class="text-yellow-700 text-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-xs">No available time slots for this date. Please choose another date or counselor.</div>';
                    selectedTime.value = '';
                    return;
                }

                timeSlots.innerHTML = '';

                const availableSlots = [...data.available_slots].sort((a, b) => a.start.localeCompare(b.start));

                availableSlots.forEach(slot => {
                    const slotElement = document.createElement('button');
                    slotElement.type = 'button';
                    slotElement.className = 'time-slot';
                    slotElement.textContent = slot.display;

                    slotElement.addEventListener('click', function() {
                        document.querySelectorAll('.time-slot').forEach(s => {
                            s.classList.remove('selected');
                        });

                        this.classList.add('selected');

                        selectedTime.value = slot.start;
                        currentSelectedSlot = slot.start;
                    });

                    slotElement.dataset.start = slot.start;
                    slotElement.dataset.end = slot.end;
                    slotElement.dataset.status = slot.status;
                    timeSlots.appendChild(slotElement);
                });
            })
            .catch(() => {
                timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg text-xs">Error loading time slots. Please try again.</div>';
            });
    }

    collegeSelect?.addEventListener('change', function() {
        counselorIdInput.value = this.value;
        populateStudentsForAssignment(this.value);
        selectedDate = null;
        dateSelect.value = '';
        selectedTime.value = '';
        loadMonthAvailability();
        loadAvailableSlots();
    });

    calendarPrev?.addEventListener('click', function() {
        const prevMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() - 1, 1);
        const minMonth = new Date(minDate.getFullYear(), minDate.getMonth(), 1);
        if (prevMonth < minMonth) return;
        currentMonth = prevMonth;
        loadMonthAvailability();
    });

    calendarNext?.addEventListener('click', function() {
        currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 1);
        loadMonthAvailability();
    });

    overrideCheck?.addEventListener('change', function() {
        loadMonthAvailability();
        renderCalendar();
        loadAvailableSlots();
    });

    studentSelect?.addEventListener('change', function() {
        updateBookingTypeOptions();
    });

    populateStudentsForAssignment(getSelectedAssignmentId());
    updateBookingTypeOptions();
    loadMonthAvailability();
    renderCalendar();
});

function toggleCreateOverride(enabled) {
    if (!enabled) {
        closeCreateOverrideModal();
        clearCreateManualOverride();
    }
}

function openCreateOverrideModal() {
    const modal = document.getElementById('createManualOverrideModal');
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
}

function closeCreateOverrideModal() {
    const modal = document.getElementById('createManualOverrideModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
}

function clearCreateManualOverride() {
    const manualDate = document.getElementById('createManualDate');
    const manualTime = document.getElementById('createManualTime');
    if (manualDate) manualDate.value = '';
    if (manualTime) manualTime.value = '';
}

function applyCreateManualOverride() {
    const manualDate = document.getElementById('createManualDate');
    const manualTime = document.getElementById('createManualTime');
    const dateSelect = document.getElementById('dateSelect');
    const selectedTime = document.getElementById('selectedTime');
    const overrideCheck = document.getElementById('createOverrideCheck');

    const d = manualDate?.value;
    const t = manualTime?.value;

    if (!d || !t) {
        alert('Please provide both date and time, or click Clear to cancel manual override.');
        return;
    }

    dateSelect.value = d;
    selectedTime.value = t;

    closeCreateOverrideModal();

    // Let the existing change listener refresh availability and slots
    overrideCheck?.dispatchEvent(new Event('change'));
}
</script>
@endsection