@extends('layouts.student')

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

    .booking-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .booking-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .booking-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .booking-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .form-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .form-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .panel-card::before, .glass-card::before, .form-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .panel-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-icon {
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.9);
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
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

    .primary-btn, .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        font-size: 0.8rem; padding: 0.55rem 1rem;
    }
    .primary-btn {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        color: var(--text-primary); background: rgba(255,255,255,0.95);
        border: 1px solid var(--border-soft);
    }
    .secondary-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; }
    .input-field, .select-field, .textarea-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.95); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .textarea-field { padding: 0.75rem; resize: vertical; min-height: 4rem; }
    .input-field:focus, .select-field:focus, .textarea-field:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }
    .input-field:disabled, .select-field:disabled { background: rgba(245,240,235,0.6); color: var(--text-muted); cursor: not-allowed; }

    .radio-label, .checkbox-label {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-size: 0.8rem; color: var(--text-primary); cursor: pointer;
    }
    .radio-label input[type="radio"], .checkbox-label input[type="checkbox"] {
        width: 1rem; height: 1rem; accent-color: var(--maroon-700);
    }

    .calendar-card {
        border: 1px solid var(--border-soft); border-radius: 0.75rem;
        background: white; padding: 1rem;
    }
    .calendar-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    .calendar-nav-btn {
        width: 2rem; height: 2rem; border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--border-soft); background: white;
        color: var(--text-secondary); font-weight: 600; cursor: pointer;
        transition: all 0.15s ease;
    }
    .calendar-nav-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); color: var(--maroon-700); }
    .calendar-month { font-size: 0.85rem; font-weight: 700; color: var(--text-primary); }
    .calendar-days {
        display: grid; grid-template-columns: repeat(7, 1fr);
        gap: 0.25rem; margin-bottom: 0.5rem;
    }
    .calendar-day-header {
        text-align: center; font-size: 0.65rem; font-weight: 600;
        color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;
    }
    .calendar-grid {
        display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.35rem;
    }
    .calendar-date-btn {
        width: 2rem; height: 2rem; border-radius: 0.5rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 500; border: 1px solid transparent;
        color: var(--text-muted); cursor: not-allowed; transition: all 0.15s ease;
    }
    .calendar-date-btn.available {
        border-color: rgba(212,175,55,0.4); color: var(--maroon-700);
        background: rgba(212,175,55,0.1); cursor: pointer;
    }
    .calendar-date-btn.available:hover {
        background: rgba(212,175,55,0.2); border-color: var(--gold-400);
    }
    .calendar-date-btn.selected {
        background: var(--maroon-700); color: white; border-color: var(--maroon-700);
    }
    .calendar-status {
        margin-top: 0.5rem; font-size: 0.7rem; color: var(--text-muted);
    }
    .calendar-status.success { color: #065f46; }
    .calendar-status.error { color: #b91c1c; }

    .time-slots-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem;
    }
    @media (min-width: 768px) { .time-slots-grid { grid-template-columns: repeat(3, 1fr); } }
    .time-slot-btn {
        padding: 0.6rem 0.75rem; border-radius: 0.5rem;
        border: 2px solid var(--border-soft); background: white;
        font-size: 0.75rem; font-weight: 500; color: var(--text-primary);
        text-align: center; cursor: pointer; transition: all 0.15s ease;
    }
    .time-slot-btn:hover { border-color: var(--maroon-700); background: rgba(254,249,231,0.6); }
    .time-slot-btn.selected {
        border-color: var(--maroon-700); background: rgba(212,175,55,0.15);
        color: var(--maroon-800); font-weight: 600;
    }
    .time-slot-placeholder {
        padding: 1rem; border: 2px dashed var(--border-soft);
        border-radius: 0.5rem; text-align: center;
        font-size: 0.75rem; color: var(--text-muted);
    }

    .modal-overlay {
        position: fixed; inset: 0; background: rgba(44,36,32,0.5);
        display: flex; align-items: center; justify-content: center;
        z-index: 50; padding: 1rem;
    }
    .modal-overlay.hidden { display: none; }
    .modal-card {
        background: white; border-radius: 0.75rem; border: 1px solid var(--border-soft);
        box-shadow: 0 8px 32px rgba(44,36,32,0.12); max-width: 42rem; width: 100%;
        overflow: hidden; display: flex; flex-direction: column; max-height: 90vh;
    }
    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft);
        background: rgba(250,248,245,0.6); flex-shrink: 0;
    }
    .modal-title { font-size: 0.9rem; font-weight: 700; color: var(--text-primary); }
    .modal-close {
        background: none; border: none; color: var(--text-muted);
        font-size: 1.1rem; cursor: pointer; transition: color 0.15s ease;
        width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;
        border-radius: 999px;
    }
    .modal-close:hover { background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .modal-body {
        padding: 1rem 1.25rem; overflow-y: auto; flex: 1;
        font-size: 0.8rem; color: var(--text-primary); line-height: 1.6;
    }
    .modal-body h3 {
        font-size: 0.85rem; font-weight: 700; color: var(--text-primary);
        margin: 1rem 0 0.5rem;
    }
    .modal-body ol { padding-left: 1.25rem; margin: 0.5rem 0; }
    .modal-body li { margin: 0.25rem 0; }
    .scroll-indicator {
        position: sticky; bottom: 0; padding: 0.5rem;
        background: rgba(254,249,231,0.95); border: 1px solid rgba(212,175,55,0.3);
        border-radius: 0.5rem; text-align: center; font-size: 0.7rem;
        color: var(--maroon-800); font-weight: 600;
    }
    .modal-footer {
        padding: 0.85rem 1.25rem; border-top: 1px solid var(--border-soft);
        background: rgba(250,248,245,0.6); flex-shrink: 0;
        display: flex; flex-direction: column; gap: 0.75rem;
    }
    .modal-footer-row {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
        gap: 0.75rem;
    }
    .modal-hint {
        font-size: 0.7rem; color: var(--text-muted); text-align: center;
    }
    .modal-hint.success { color: #065f46; }

    .field-help {
        font-size: 0.7rem; color: var(--text-muted); margin-top: 0.35rem;
    }
    .field-help.error { color: #b91c1c; }
    .field-help.success { color: #065f46; }

    .loading-text {
        font-size: 0.75rem; color: var(--maroon-700); font-weight: 500;
    }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .input-field, .select-field, .textarea-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; padding: 0.7rem; font-size: 0.75rem; }
        .btn-row-mobile { flex-direction: column; gap: 0.75rem !important; }
        .hero-card { padding: 1rem !important; }
        .hero-icon { width: 2.25rem; height: 2.25rem; }
        .calendar-date-btn { width: 1.75rem; height: 1.75rem; font-size: 0.7rem; }
        .time-slots-grid { grid-template-columns: 1fr 1fr; }
        .modal-footer-row { flex-direction: column; align-items: stretch; }
        .checkbox-label { font-size: 0.75rem; }
    }
</style>

<div class="min-h-screen booking-shell">
    <div class="booking-glow one"></div>
    <div class="booking-glow two"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex items-start gap-3">
                    <div class="hero-icon">
                        <i class="fas fa-calendar-plus text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="hero-badge">
                            <span class="hero-badge-dot"></span>
                            New Appointment
                        </div>
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Book an Appointment</h1>
                        <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                            Schedule a counseling session with our guidance team.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST" class="p-4 sm:p-6">
                @csrf

                <!-- Counselor Selection -->
                <div class="mb-6">
                    <label class="field-label">Counselor</label>

                    <!-- Counselor Type Selection -->
                    <div class="mb-4" id="counselorTypeWrapper">
                        <label class="radio-label">
                            <input type="radio" name="counselor_type" value="college" checked
                                   class="counselor-type-radio">
                            <span>{{ ($allowAllCounselors ?? false) ? 'Counselors from all colleges' : 'Counselors from my college' }}</span>
                        </label>
                        <label class="radio-label ml-4 sm:ml-6" id="referredCounselorOption">
                            <input type="radio" name="counselor_type" value="referred"
                                   class="counselor-type-radio">
                            <span>Previously referred counselors</span>
                        </label>
                    </div>

                    <select name="counselor_id" id="counselorSelect" class="select-field" required>
                        <option value="">Choose a counselor</option>
                        <!-- Options will be populated by JavaScript -->
                    </select>
                    <input type="hidden" name="counselor_id" id="counselorAutoAssignedInput">
                    <p id="counselorAutoAssigned" class="hidden mt-2 text-[0.75rem] text-[#6b5e57]"></p>

                    <!-- Loading indicator -->
                    <div id="counselorLoading" class="hidden mt-2 loading-text">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Loading counselors...
                    </div>
                </div>

                <!-- Booking Type -->
                <div class="mb-6">
                    <label class="field-label">Type of Booking</label>
                    <select name="booking_type" id="bookingType" class="select-field" required>
                        <option value="">Choose a booking type</option>
                        <option value="Initial Interview" id="bookingTypeInitial" {{ ($hasInitialInterviewAppointment ?? false) ? 'disabled hidden' : '' }}>Initial Interview</option>
                        <option value="Counseling">Counseling</option>
                        <option value="Consultation">Consultation</option>
                    </select>
                    <p class="field-help" id="bookingTypeHelp">
                        Select the reason for your appointment.
                    </p>
                </div>

                <!-- Reason / Concern Category -->
                <div class="mb-6">
                    <label class="field-label">Reason / Concern Category</label>
                    <select name="concern_category" id="concernCategory" class="select-field" required>
                        <option value="">Choose a reason or concern</option>
                        <option value="Academic Stress">Academic Stress</option>
                        <option value="Personal Problem">Personal Problem</option>
                        <option value="Family Concern">Family Concern</option>
                        <option value="Relationship Concern">Relationship Concern</option>
                        <option value="Emotional / Mental Well-being">Emotional / Mental Well-being</option>
                        <option value="Peer / Social Concern">Peer / Social Concern</option>
                        <option value="Career / Future Uncertainty">Career / Future Uncertainty</option>
                        <option value="Financial Stress">Financial Stress</option>
                        <option value="Self-esteem / Confidence">Self-esteem / Confidence</option>
                        <option value="Other">Other</option>
                    </select>
                    <p class="field-help">
                        Static dropdown for future use. Students can choose the closest reason for booking.
                    </p>
                </div>

                <!-- Mood Rating -->
                <div class="mb-6">
                    <label class="field-label">How are you feeling today?</label>
                    <select name="mood_rating" id="moodRating" class="select-field" required>
                        <option value="">Choose your current mood</option>
                        <option value="1 - Very Overwhelmed">1 - Very Overwhelmed</option>
                        <option value="2 - Struggling">2 - Struggling</option>
                        <option value="3 - Not Okay">3 - Not Okay</option>
                        <option value="4 - A Little Down">4 - A Little Down</option>
                        <option value="5 - Neutral">5 - Neutral</option>
                        <option value="6 - A Bit Better">6 - A Bit Better</option>
                        <option value="7 - Doing Fine">7 - Doing Fine</option>
                        <option value="8 - Good">8 - Good</option>
                        <option value="9 - Very Good">9 - Very Good</option>
                        <option value="10 - Great">10 - Great</option>
                    </select>
                    <p class="field-help">
                        Static mood check-in for future reporting or triage use.
                    </p>
                </div>

                <!-- Date Selection -->
                <div class="mb-6">
                    <label class="field-label">Select Date</label>
                    <div id="appointmentCalendar" class="calendar-card">
                        <div class="calendar-header">
                            <button type="button" id="calendarPrev" class="calendar-nav-btn" aria-label="Previous month">
                                ‹
                            </button>
                            <h3 id="calendarMonthLabel" class="calendar-month"></h3>
                            <button type="button" id="calendarNext" class="calendar-nav-btn" aria-label="Next month">
                                ›
                            </button>
                        </div>
                        <div class="calendar-days">
                            <span class="calendar-day-header">Sun</span>
                            <span class="calendar-day-header">Mon</span>
                            <span class="calendar-day-header">Tue</span>
                            <span class="calendar-day-header">Wed</span>
                            <span class="calendar-day-header">Thu</span>
                            <span class="calendar-day-header">Fri</span>
                            <span class="calendar-day-header">Sat</span>
                        </div>
                        <div id="calendarGrid" class="calendar-grid"></div>
                        <p id="calendarStatus" class="calendar-status">
                            Select a counselor to load available dates.
                        </p>
                    </div>
                    <input type="hidden" name="appointment_date" id="dateSelect"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                </div>

                <!-- Time Slots -->
                <div class="mb-6">
                    <label class="field-label">Available Time Slots</label>
                    <div id="timeSlots" class="time-slots-grid">
                        <div class="time-slot-placeholder">
                            Select a counselor and date to see available time slots
                        </div>
                    </div>
                    <input type="hidden" name="start_time" id="selectedTime" required>
                </div>

                <!-- Concern -->
                <div class="mb-6">
                    <label class="field-label">Presenting Problem</label>
                    <textarea name="concern" rows="4" class="textarea-field"
                              placeholder="Briefly describe the reason for your appointment" required></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 sm:space-x-4 btn-row-mobile">
                    <a href="{{ route('appointments.index') }}" class="secondary-btn px-5 py-2.5 text-xs sm:text-sm">
                        Cancel
                    </a>
                    <button type="button" id="openConsentModal" class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                        <i class="fas fa-calendar-check mr-1.5 text-[9px] sm:text-xs"></i>
                        <span>Book Now</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Informed Consent Modal -->
<div id="consentModal" class="modal-overlay hidden">
    <div class="modal-card">
        <div class="modal-header">
            <h2 class="modal-title">INFORMED CONSENT FOR COUNSELING</h2>
            <button type="button" class="modal-close" data-consent-close aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="consentContent" class="modal-body">
            <p>
                COUNSELING is a confidential process designed to help you address your concerns, come to a better understanding
                of yourself, and learn effective personal and interpersonal coping strategies. It involves a relationship between
                you and a trained Counselor who has the desire and willingness to help you accomplish your individual goals.
            </p>
            <p>
                Counseling involves sharing sensitive, personal, and private information that may at times be distressing. During
                the course of counseling, there may be periods of increased anxiety or confusion. The outcome of counseling is
                often positive; however, the level of satisfaction for any individual is not predictable. Your counselor is
                available to support you throughout the counseling process.
            </p>
            <h3>CONFIDENTIALITY</h3>
            <p>
                All interactions with the counseling services of the Office of Guidance and Counseling (OGC), including scheduling
                of or attendance at appointments, content of your sessions, progress in counseling, and your records are confidential.
                No record of counseling is contained in any academic, educational or job placement file. You may request in writing
                that the counselor releases specific information about your counseling to persons you designate.
            </p>
            <h3>EXCEPTIONS TO CONFIDENTIALITY</h3>
            <p>Under the following circumstances can only there be a breach in confidentiality:</p>
            <ol class="list-decimal list-inside space-y-2">
                <li>
                    The counseling staff works as a team. Your counselor may consult with other counselors to provide the best possible
                    care. These case consultation/case conferences are for professional training purposes; and do not usually include
                    any identifiers of the client.
                </li>
                <li>
                    If there is evidence of clear and imminent danger or harm to yourself and/or others, a Counselor is legally required
                    to report this information to the authorities responsible for ensuring safety.
                </li>
                <li>
                    The staff of the Office of Guidance and Counseling who learn of, or strongly suspect physical or sexual abuse or neglect
                    of a person under 18 years of age, must report this information to local authorities for child protection services (RA 7610).
                </li>
                <li>
                    A court order, issued by a competent judge, may require the counselor to release information contained in records and/or
                    require a counselor to testify in court hearing.
                </li>
            </ol>
            <h3>CLIENT'S ROLES</h3>
            <ol class="list-decimal list-inside space-y-2">
                <li>
                    The client further agrees to willingly cooperate in attending scheduled/booked counseling sessions, follow-up and/or
                    tutorial sessions, and accomplish assigned homework/s as agreed by both parties.
                </li>
                <li>
                    The client understands that as he/she seeks professional help, the counseling relationship established shall come to a
                    termination or closure after careful evaluation and discretion of the attending counselor. There is NO FEE for counseling
                    services availed by students within the Institute.
                </li>
            </ol>
            <h3>REFERRAL TO EXPERTS</h3>
            <p>
                If you are referred off campus to health, mental health or substance abuse professionals, you are responsible for their charges,
                except when referred to clinicians who have memorandum of understanding/MOU with the Institute.
            </p>
            
            <div id="scrollIndicator" class="scroll-indicator">
                ↓ Scroll to bottom to enable confirmation ↓
            </div>
        </div>
        
        <div class="modal-footer">
            <div class="modal-footer-row">
                <label class="checkbox-label">
                    <input type="checkbox" id="consentAcknowledged" disabled>
                    <span>I have read and understood the Informed Consent for Counseling.</span>
                </label>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            class="secondary-btn px-4 py-2 text-xs"
                            data-consent-close>
                        Cancel
                    </button>
                    <button type="button"
                            id="confirmBooking"
                            class="primary-btn px-5 py-2 text-xs"
                            disabled>
                        Confirm Booking
                    </button>
                </div>
            </div>
            <p id="consentHint" class="modal-hint">
                Please scroll through the entire document to enable the checkbox.
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const counselorTypeRadios = document.querySelectorAll('.counselor-type-radio');
    const counselorSelect = document.getElementById('counselorSelect');
    const counselorTypeWrapper = document.getElementById('counselorTypeWrapper');
    const counselorAutoAssigned = document.getElementById('counselorAutoAssigned');
    const counselorAutoAssignedInput = document.getElementById('counselorAutoAssignedInput');
    const counselorLoading = document.getElementById('counselorLoading');
    const dateSelect = document.getElementById('dateSelect');
    const calendarGrid = document.getElementById('calendarGrid');
    const calendarMonthLabel = document.getElementById('calendarMonthLabel');
    const calendarPrev = document.getElementById('calendarPrev');
    const calendarNext = document.getElementById('calendarNext');
    const calendarStatus = document.getElementById('calendarStatus');
    const timeSlots = document.getElementById('timeSlots');
    const selectedTime = document.getElementById('selectedTime');
    const bookingTypeSelect = document.getElementById('bookingType');
    const bookingTypeInitial = document.getElementById('bookingTypeInitial');
    const bookingTypeHelp = document.getElementById('bookingTypeHelp');
    const referredCounselorOption = document.getElementById('referredCounselorOption');
    const concernCategorySelect = document.getElementById('concernCategory');
    const moodRatingSelect = document.getElementById('moodRating');

    const openConsentModal = document.getElementById('openConsentModal');
    const consentModal = document.getElementById('consentModal');
    const consentContent = document.getElementById('consentContent');
    const consentCheckbox = document.getElementById('consentAcknowledged');
    const confirmBooking = document.getElementById('confirmBooking');
    const consentHint = document.getElementById('consentHint');
    const scrollIndicator = document.getElementById('scrollIndicator');
    const consentCloseButtons = document.querySelectorAll('[data-consent-close]');
    const appointmentForm = document.getElementById('appointmentForm');

    const studentYearLevel = {!! json_encode(optional($student)->year_level) !!};
    const studentInitialInterviewCompleted = {!! json_encode(optional($student)->initial_interview_completed) !!};
    const hasInitialInterviewAppointment = {!! json_encode($hasInitialInterviewAppointment ?? false) !!};
    const allowAllCounselors = {!! json_encode($allowAllCounselors ?? false) !!};

    let currentSelectedSlot = null;
    const minDate = new Date();
    minDate.setHours(0, 0, 0, 0);
    minDate.setDate(minDate.getDate() + 1);
    let currentMonth = new Date(minDate.getFullYear(), minDate.getMonth(), 1);
    let selectedDate = null;
    let availabilityByDate = new Map();
    let availabilityRequestId = 0;

    let collegeCounselors = {!! json_encode($counselors->map(function($c) {
        return [
            'id' => $c->id,
            'name' => $c->user->first_name . ' ' . $c->user->last_name,
            'position' => $c->position,
            'college' => $c->college->name ?? 'N/A',
            'display_text' => $c->user->first_name . ' ' . $c->user->last_name . ' - ' . $c->position . ' (' . ($c->college->name ?? 'N/A') . ')'
        ];
    })) !!};

    function validateForm() {
        const counselorId = getActiveCounselorId();
        const bookingType = bookingTypeSelect.value;
        const concernCategory = concernCategorySelect.value;
        const moodRating = moodRatingSelect.value;
        const date = dateSelect.value;
        const time = selectedTime.value;
        const concern = document.querySelector('textarea[name="concern"]').value.trim();

        if (!counselorId) {
            alert('Please select a counselor');
            return false;
        }
        if (!bookingType) {
            alert('Please select a booking type');
            return false;
        }
        if (!concernCategory) {
            alert('Please select a reason or concern category');
            return false;
        }
        if (!moodRating) {
            alert('Please select how you are feeling today');
            return false;
        }
        if (!date) {
            alert('Please select a date');
            return false;
        }
        if (!time) {
            alert('Please select a time slot');
            return false;
        }
        if (!concern) {
            alert('Please describe your presenting problem');
            return false;
        }
        return true;
    }

    function openModal() {
        if (!validateForm()) {
            return;
        }
        
        consentModal?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        if (consentContent) {
            consentContent.scrollTop = 0;
        }
        
        consentCheckbox.checked = false;
        consentCheckbox.disabled = true;
        confirmBooking.disabled = true;
        
        if (scrollIndicator) {
            scrollIndicator.style.display = 'block';
        }
        
        consentHint.textContent = 'Please scroll through the entire document to enable the checkbox.';
        consentHint.classList.remove('success', 'error');
    }

    function closeModal() {
        consentModal?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    consentContent?.addEventListener('scroll', function() {
        const scrollThreshold = this.scrollHeight - this.clientHeight - 10;
        const isAtBottom = this.scrollTop >= scrollThreshold;
        
        if (isAtBottom) {
            consentCheckbox.disabled = false;
            if (scrollIndicator) {
                scrollIndicator.style.display = 'none';
            }
            consentHint.textContent = 'You can now acknowledge and confirm your booking.';
            consentHint.classList.add('success');
        }
    });

    consentCheckbox?.addEventListener('change', function() {
        confirmBooking.disabled = !this.checked;
    });

    confirmBooking?.addEventListener('click', function() {
        if (consentCheckbox.checked) {
            const consentInput = document.createElement('input');
            consentInput.type = 'hidden';
            consentInput.name = 'consent_acknowledged';
            consentInput.value = '1';
            appointmentForm.appendChild(consentInput);
            
            appointmentForm.submit();
        }
    });

    openConsentModal?.addEventListener('click', openModal);

    consentCloseButtons.forEach(button => {
        button.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !consentModal?.classList.contains('hidden')) {
            closeModal();
        }
    });

    consentModal?.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            closeModal();
        }
    });

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

    function getActiveCounselorId() {
        return counselorSelect.value || counselorAutoAssignedInput.value;
    }

    function setCalendarStatus(message, tone = 'muted') {
        if (!calendarStatus) return;
        calendarStatus.textContent = message;
        calendarStatus.classList.remove('success', 'error');
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

        const counselorId = getActiveCounselorId();
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
            const availabilityKnown = availabilityByDate.has(dateValue);
            const isAvailable = availabilityByDate.get(dateValue) === true;
            const isDisabled = !counselorId || isPast || !availabilityKnown || !isAvailable;

            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = day;
            button.disabled = isDisabled;
            button.className = 'calendar-date-btn';

            if (!isDisabled) {
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
        const counselorId = getActiveCounselorId();
        availabilityByDate = new Map();
        renderCalendar();

        if (!counselorId) {
            setCalendarStatus('Select a counselor to load available dates.');
            return;
        }

        const requestId = ++availabilityRequestId;
        setCalendarStatus('Checking available dates...');
        const monthValue = `${currentMonth.getFullYear()}-${String(currentMonth.getMonth() + 1).padStart(2, '0')}`;

        try {
            const response = await fetch(`/appointments/available-dates?counselor_id=${counselorId}&month=${monthValue}`);
            const data = await response.json();
            if (requestId !== availabilityRequestId) return;
            const availability = data.availability || {};
            Object.keys(availability).forEach(dateValue => {
                availabilityByDate.set(dateValue, availability[dateValue] === true);
            });
        } catch (error) {
            if (requestId !== availabilityRequestId) return;
        }

        if (requestId !== availabilityRequestId) return;

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

    function loadCounselors(type) {
        counselorSelect.innerHTML = '<option value="">Choose a counselor</option>';
        counselorLoading.classList.remove('hidden');

        if (type === 'college') {
            populateCounselorSelect(collegeCounselors);
            counselorLoading.classList.add('hidden');
        } else {
            fetch('/appointments/referred-counselors')
                .then(response => response.json())
                .then(data => {
                    populateCounselorSelect(data);
                    counselorLoading.classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error loading referred counselors:', error);
                    counselorLoading.classList.add('hidden');
                    counselorSelect.innerHTML = '<option value="">Error loading counselors</option>';
                });
        }
    }

    function populateCounselorSelect(counselors) {
        counselorSelect.innerHTML = '<option value="">Choose a counselor</option>';

        if (counselors.length === 0) {
            counselorSelect.innerHTML = '<option value="">No counselors available</option>';
            counselorSelect.disabled = true;
            counselorSelect.classList.remove('hidden');
            counselorAutoAssigned.classList.add('hidden');
            return;
        }

        counselors.forEach(counselor => {
            const option = document.createElement('option');
            option.value = counselor.id;
            option.textContent = counselor.display_text || counselor.name;
            counselorSelect.appendChild(option);
        });

        if (counselors.length === 1) {
            const onlyCounselor = counselors[0];
            counselorSelect.value = onlyCounselor.id;
            counselorSelect.disabled = true;
            counselorSelect.classList.add('hidden');
            counselorAutoAssigned.textContent = `Assigned counselor: ${onlyCounselor.display_text || onlyCounselor.name}`;
            counselorAutoAssigned.classList.remove('hidden');
            counselorAutoAssignedInput.value = onlyCounselor.id;
            loadMonthAvailability();
            if (dateSelect.value) {
                loadAvailableSlots();
            }
        } else {
            counselorSelect.disabled = false;
            counselorSelect.classList.remove('hidden');
            counselorAutoAssigned.classList.add('hidden');
            counselorAutoAssignedInput.value = '';
            loadMonthAvailability();
        }
    }

    function updateBookingTypeOptions() {
        const isFirstYear = studentYearLevel === '1st Year';
        const isSecondYear = studentYearLevel === '2nd Year';
        const isUpperYear = !isFirstYear && !isSecondYear;
        const hasCompletedInitialInterview = studentInitialInterviewCompleted === true;
        const needsInitialInterview = isFirstYear || isSecondYear;

        const counselingOption = bookingTypeSelect.querySelector('option[value="Counseling"]');
        const consultationOption = bookingTypeSelect.querySelector('option[value="Consultation"]');

        if (hasCompletedInitialInterview) {
            if (bookingTypeSelect.value === 'Initial Interview') {
                bookingTypeSelect.value = '';
            }
            bookingTypeInitial.disabled = true;
            bookingTypeInitial.hidden = true;
            bookingTypeSelect.querySelectorAll('option').forEach(option => {
                option.disabled = false;
            });
            bookingTypeHelp.textContent = 'Initial Interview is already completed.';
            return;
        }

        if (hasInitialInterviewAppointment) {
            if (bookingTypeSelect.value === 'Initial Interview') {
                bookingTypeSelect.value = '';
            }
            bookingTypeInitial.disabled = true;
            bookingTypeInitial.hidden = true;
            bookingTypeSelect.querySelectorAll('option').forEach(option => {
                option.disabled = false;
            });

            if (needsInitialInterview) {
                counselingOption && (counselingOption.disabled = true);
                consultationOption && (consultationOption.disabled = true);
                if (bookingTypeSelect.value === 'Counseling' || bookingTypeSelect.value === 'Consultation') {
                    bookingTypeSelect.value = '';
                }
                bookingTypeHelp.textContent = 'You can book Counseling or Consultation only after your Initial Interview is completed.';
                return;
            }

            bookingTypeHelp.textContent = 'Initial Interview is already booked.';
            return;
        }

        if (needsInitialInterview && !hasCompletedInitialInterview) {
            counselingOption && (counselingOption.disabled = true);
            consultationOption && (consultationOption.disabled = true);
            if (bookingTypeSelect.value === 'Counseling' || bookingTypeSelect.value === 'Consultation') {
                bookingTypeSelect.value = '';
            }

            if (!isFirstYear) {
                bookingTypeInitial.disabled = false;
                bookingTypeInitial.hidden = false;
                bookingTypeHelp.textContent = 'You can book Counseling or Consultation only after your Initial Interview is completed.';
                return;
            }
        }

        if (isFirstYear) {
            bookingTypeInitial.disabled = false;
            bookingTypeInitial.hidden = false;
            bookingTypeSelect.value = 'Initial Interview';
            bookingTypeSelect.querySelectorAll('option').forEach(option => {
                option.disabled = option.value !== 'Initial Interview';
            });
            bookingTypeHelp.textContent = 'Initial Interview is required for 1st year students.';
            return;
        }

        bookingTypeSelect.querySelectorAll('option').forEach(option => {
            option.disabled = false;
        });

        if (isUpperYear) {
            if (bookingTypeSelect.value === 'Initial Interview') {
                bookingTypeSelect.value = '';
            }
            bookingTypeInitial.disabled = true;
            bookingTypeInitial.hidden = true;
            bookingTypeHelp.textContent = 'Initial Interview is not available for your year level.';
            return;
        }

        bookingTypeInitial.disabled = false;
        bookingTypeInitial.hidden = false;
        bookingTypeHelp.textContent = 'Initial Interview is available if you have not completed it yet.';
    }

    function checkReferredCounselorsAvailability() {
        if (allowAllCounselors) {
            counselorTypeWrapper?.classList.add('hidden');
            return;
        }
        fetch('/appointments/referred-counselors')
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    const referredRadio = referredCounselorOption?.querySelector('input[type="radio"]');
                    if (referredRadio) {
                        referredRadio.disabled = true;
                        referredRadio.checked = false;
                    }
                    referredCounselorOption?.classList.add('opacity-50', 'cursor-not-allowed');
                    referredCounselorOption?.setAttribute('title', 'No referred counselors available');
                    counselorTypeWrapper?.classList.add('hidden');
                } else {
                    counselorTypeWrapper?.classList.remove('hidden');
                }
            })
            .catch(() => {});
    }

    function loadAvailableSlots() {
        const counselorId = getActiveCounselorId();
        const date = dateSelect.value;

        if (!counselorId || !date) {
            timeSlots.innerHTML = '<div class="time-slot-placeholder">Select a counselor and date to see available time slots</div>';
            selectedTime.value = '';
            return;
        }

        timeSlots.innerHTML = '<div class="time-slot-placeholder"><i class="fas fa-spinner fa-spin mr-1"></i>Loading available slots...</div>';

        fetch(`/appointments/available-slots?counselor_id=${counselorId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    timeSlots.innerHTML = `<div class="time-slot-placeholder" style="border-color:#fecaca;color:#b91c1c">${data.message}</div>`;
                    selectedTime.value = '';
                    return;
                }

                if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                    timeSlots.innerHTML = '<div class="time-slot-placeholder" style="border-color:#fecaca;color:#b91c1c">No working hours for this date. Please choose another date.</div>';
                    selectedTime.value = '';
                    return;
                }

                timeSlots.innerHTML = '';

                const availableSlots = [...data.available_slots].sort((a, b) =>
                    a.start.localeCompare(b.start)
                );

                if (availableSlots.length === 0) {
                    timeSlots.innerHTML = '<div class="time-slot-placeholder" style="border-color:#fde68a;color:#92400e;background:#fffbeb">No available time slots for this date. Please choose another date or counselor.</div>';
                    selectedTime.value = '';
                    return;
                }

                availableSlots.forEach(slot => {
                    const slotElement = document.createElement('button');
                    slotElement.type = 'button';
                    slotElement.className = 'time-slot-btn';
                    slotElement.textContent = slot.display;

                    slotElement.addEventListener('click', function() {
                        document.querySelectorAll('.time-slot-btn').forEach(s => {
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
            .catch(error => {
                console.error('Error:', error);
                timeSlots.innerHTML = '<div class="time-slot-placeholder" style="border-color:#fecaca;color:#b91c1c">Error loading time slots. Please try again.</div>';
            });
    }

    counselorTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                loadCounselors(this.value);
                timeSlots.innerHTML = '<div class="time-slot-placeholder">Select a counselor and date to see available time slots</div>';
                selectedTime.value = '';
                selectedDate = null;
                dateSelect.value = '';
                loadMonthAvailability();
            }
        });
    });

    counselorSelect.addEventListener('change', function() {
        selectedDate = null;
        dateSelect.value = '';
        selectedTime.value = '';
        loadMonthAvailability();
        loadAvailableSlots();
    });

    if (allowAllCounselors) {
        counselorTypeWrapper?.classList.add('hidden');
    }

    loadCounselors('college');
    updateBookingTypeOptions();
    checkReferredCounselorsAvailability();
    renderCalendar();

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
});
</script>
@endsection