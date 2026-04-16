@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

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

    /* Base Layout & Glow */
    .notes-shell {
        position: relative;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 3rem;
    }
    .notes-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2; z-index: 0;
    }
    .notes-glow.one { top: -50px; left: -50px; width: 250px; height: 250px; background: var(--gold-400); }
    .notes-glow.two { bottom: 10%; right: -50px; width: 220px; height: 220px; background: var(--maroon-800); }

    /* Glass Cards */
    .panel-card {
        position: relative; z-index: 1; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .panel-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    /* Header Specifics */
    .page-header h1 { color: var(--text-primary); font-weight: 700; letter-spacing: -0.02em; }
    .page-header p { color: var(--text-secondary); }
    .student-meta { color: var(--text-muted); font-size: 0.85rem; margin-top: 0.25rem; }

    /* Info Box (Appointment Context) */
    .info-box {
        background: rgba(255, 249, 230, 0.6); border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 0.75rem; padding: 1rem; display: flex; align-items: center; gap: 1rem;
    }
    .info-icon { color: var(--gold-500); font-size: 1.5rem; }
    .info-title { color: var(--maroon-800); font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem; }
    .info-sub { color: var(--maroon-700); font-size: 0.8rem; }

    /* Form Elements */
    .section-title {
        font-size: 0.8rem; font-weight: 600; color: var(--text-primary);
        text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.35rem; display: block;
    }
    
    .input-field, .select-field, .textarea-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.85rem; padding: 0.6rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus, .textarea-field:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }
    .textarea-field { resize: vertical; line-height: 1.5; }

    /* Custom Checkbox */
    .custom-checkbox {
        appearance: none; -webkit-appearance: none;
        width: 1.1rem; height: 1.1rem; border: 1px solid var(--border-soft);
        border-radius: 0.25rem; background: white; cursor: pointer;
        position: relative; transition: all 0.2s; flex-shrink: 0;
    }
    .custom-checkbox:checked {
        background: var(--maroon-700); border-color: var(--maroon-700);
    }
    .custom-checkbox:checked::after {
        content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(45deg);
        width: 0.3rem; height: 0.6rem; border: solid white; border-width: 0 2px 2px 0;
    }
    .custom-control-label {
        font-size: 0.85rem; color: var(--text-secondary); cursor: pointer; user-select: none;
    }

    /* Follow-up Section Styling */
    .followup-section {
        background: rgba(255, 249, 230, 0.4); border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 0.75rem; padding: 1.25rem; margin-top: 1.5rem;
    }
    .followup-title {
        color: var(--maroon-800); font-weight: 700; font-size: 1.1rem;
        margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .alert-note {
        background: rgba(254, 243, 199, 0.4); border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 0.6rem; padding: 0.75rem; display: flex; gap: 0.75rem; margin-bottom: 1rem;
    }
    .alert-icon { color: #d97706; margin-top: 0.15rem; }
    .alert-text { color: #92400e; font-size: 0.8rem; line-height: 1.4; }
    .alert-title { font-weight: 600; display: block; }

    .counselor-box {
        background: white; border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 0.6rem; padding: 0.75rem;
    }
    .counselor-name { color: var(--maroon-800); font-weight: 600; font-size: 0.9rem; }
    .counselor-pos { color: var(--maroon-700); font-size: 0.75rem; }

    /* Time Slots Grid */
    .slot-legend { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 0.75rem; font-size: 0.7rem; }
    .legend-item { display: flex; align-items: center; gap: 0.35rem; color: var(--maroon-700); }
    .legend-dot { width: 0.75rem; height: 0.75rem; border-radius: 0.15rem; border: 2px solid transparent; }
    .dot-available { background: rgba(134, 239, 172, 0.3); border-color: #22c55e; }
    .dot-booked { background: rgba(229, 231, 235, 0.6); border-color: #9ca3af; }
    .dot-selected { background: rgba(229, 231, 235, 0.6); border-color: var(--maroon-700); }

    .slots-grid {
        display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.5rem;
        max-height: 250px; overflow-y: auto; padding: 0.5rem;
        background: white; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 0.6rem;
    }
    .time-slot {
        padding: 0.5rem; border-radius: 0.5rem; text-align: center; font-size: 0.75rem;
        transition: all 0.2s; cursor: pointer; border: 1px solid transparent;
    }
    .time-slot.booked {
        background: rgba(229, 231, 235, 0.6); border-color: #9ca3af; color: #6b7280;
        cursor: not-allowed; pointer-events: none;
    }
    .time-slot.available {
        background: rgba(134, 239, 172, 0.2); border-color: #22c55e; color: #374151;
    }
    .time-slot.available:hover {
        background: rgba(134, 239, 172, 0.4); border-color: #16a34a;
    }
    .time-slot.selected {
        background: rgba(229, 231, 235, 0.8); border-color: var(--maroon-700); color: var(--maroon-800);
        font-weight: 600;
    }
    .slot-empty {
        grid-column: span 2; text-align: center; padding: 1.5rem;
        color: var(--gold-500); font-size: 0.8rem;
    }
    .slot-error {
        grid-column: span 2; text-align: center; padding: 1rem;
        border: 2px dashed #fca5a5; border-radius: 0.5rem; background: #fef2f2;
    }
    .slot-msg {
        grid-column: span 2; text-align: center; padding: 0.75rem;
        background: rgba(254, 243, 199, 0.4); border: 1px solid #fcd34d;
        border-radius: 0.5rem; font-size: 0.75rem; color: #92400e; margin-top: 0.5rem;
    }

    /* Recent Appointments */
    .recent-list {
        background: rgba(250,248,245,0.6); border-radius: 0.6rem; padding: 1rem;
        max-height: 200px; overflow-y: auto;
    }
    .recent-item {
        font-size: 0.75rem; color: var(--text-secondary);
        border-left: 2px solid var(--maroon-700); padding-left: 0.75rem; margin-bottom: 0.5rem;
    }
    .recent-item strong { color: var(--text-primary); }
    .has-notes { color: #059669; margin-left: 0.5rem; }

    /* Error Message */
    .error-msg { color: #b91c1c; font-size: 0.75rem; margin-top: 0.35rem; font-weight: 500; }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: #fef9e7; font-weight: 600; border-radius: 0.6rem;
        padding: 0.75rem 1.5rem; display: inline-flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 10px rgba(92,26,26,0.15); transition: all 0.2s ease;
        border: none; text-decoration: none;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    
    .btn-secondary {
        background: white; color: var(--text-secondary); border: 1px solid var(--border-soft);
        font-weight: 600; border-radius: 0.6rem; padding: 0.75rem 1.5rem;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s ease; text-decoration: none;
    }
    .btn-secondary:hover { background: var(--bg-warm); color: var(--text-primary); border-color: var(--maroon-700); }

    /* Mobile Adjustments */
    @media (max-width: 639px) {
        .panel-card { padding: 1rem; }
        .btn-primary, .btn-secondary { width: 100%; justify-content: center; }
        .action-group { flex-direction: column-reverse; gap: 0.75rem; }
        .action-group > * { width: 100%; }
        .info-box { flex-direction: column; align-items: flex-start; }
        .slots-grid { grid-template-columns: 1fr; }
        .slot-empty, .slot-error, .slot-msg { grid-column: span 1; }
    }
</style>

<div class="min-h-screen notes-shell">
    <div class="notes-glow one"></div>
    <div class="notes-glow two"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        
        <!-- Header -->
        <div class="mb-6 panel-card p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="page-header">
                    <h1 class="text-xl sm:text-2xl font-bold">Create Session Notes</h1>
                    <p class="font-medium mt-1">
                        for {{ $student->user->first_name }} {{ $student->user->last_name }}
                        <span class="text-[var(--text-muted)] font-normal">({{ $student->student_id }})</span>
                    </p>
                    <div class="student-meta">
                        {{ $student->college->name ?? 'N/A' }} • {{ $student->year_level }}
                    </div>
                    
                    @if($appointment)
                        <div class="info-box mt-3">
                            <i class="fas fa-calendar-days info-icon"></i>
                            <div>
                                <div class="info-title">Creating notes for appointment on {{ $appointment->appointment_date->format('F j, Y') }}</div>
                                <div class="info-sub">Time: {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}</div>
                            </div>
                        </div>
                    @endif
                </div>
                <a href="{{ route('counselor.session-notes.index', $student) }}"
                   class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Notes
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="panel-card p-5 sm:p-6 md:p-8">
            <form action="{{ route('counselor.session-notes.store', $student) }}" method="POST">
                @csrf

                @if($appointment)
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    <!-- Session Date -->
                    <div>
                        <label for="session_date" class="section-title">Session Date *</label>
                        <input type="date" name="session_date" id="session_date"
                               value="{{ old('session_date', $appointment ? $appointment->appointment_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                               class="input-field" required>
                        @error('session_date')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Session Type -->
                    <div>
                        <label for="session_type" class="section-title">Session Type *</label>
                        <select name="session_type" id="session_type" class="select-field" required>
                            <option value="">Select session type</option>
                            @foreach($sessionTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('session_type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('session_type')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mood Level -->
                    <div>
                        <label for="mood_level" class="section-title">Student's Mood Level</label>
                        <select name="mood_level" id="mood_level" class="select-field">
                            <option value="">Select mood level</option>
                            @foreach($moodLevels as $value => $label)
                                <option value="{{ $value }}" {{ old('mood_level') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('mood_level')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Requires Follow-up -->
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-[rgba(250,248,245,0.6)] border border-[var(--border-soft)] md:col-span-2">
                        <input type="checkbox" name="requires_follow_up" id="requires_follow_up" value="1"
                               {{ old('requires_follow_up') ? 'checked' : '' }}
                               class="custom-checkbox">
                        <label for="requires_follow_up" class="custom-control-label font-medium text-[var(--text-primary)]">
                            Schedule follow-up appointment
                        </label>
                    </div>
                </div>

                <!-- Follow-up Appointment Scheduling -->
                <div id="followup_appointment_container" class="followup-section hidden">
                    <h4 class="followup-title">
                        <i class="fas fa-calendar-plus"></i> Schedule Follow-up Appointment
                    </h4>
                    
                    <div class="alert-note">
                        <i class="fas fa-circle-info alert-icon"></i>
                        <div class="alert-text">
                            <span class="alert-title">Note about follow-up appointments:</span>
                            This will schedule a new appointment for the student. Session notes for the follow-up should be created after that appointment occurs.
                        </div>
                    </div>

                    <!-- Counselor Selection -->
                    <div class="mb-4">
                        <label class="section-title">Counselor</label>
                        <div class="counselor-box">
                            <p class="counselor-name">
                                {{ Auth::user()->counselor->user->first_name }} {{ Auth::user()->counselor->user->last_name }}
                            </p>
                            <p class="counselor-pos">{{ Auth::user()->counselor->position }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                        <!-- Follow-up Date -->
                        <div class="md:col-span-2">
                            <label for="followup_appointment_date" class="section-title">Follow-up Date *</label>
                            <input type="date" name="followup_appointment_date" id="followup_appointment_date"
                                   value="{{ old('followup_appointment_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="input-field">
                            @error('followup_appointment_date')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Available Time Slots -->
                        <div class="md:col-span-2">
                            <label class="section-title">Available Time Slots</label>

                            <!-- Legend -->
                            <div class="slot-legend">
                                <div class="legend-item"><div class="legend-dot dot-available"></div> Available</div>
                                <div class="legend-item"><div class="legend-dot dot-booked"></div> Booked</div>
                                <div class="legend-item"><div class="legend-dot dot-selected"></div> Selected</div>
                            </div>

                            <div id="followup_time_slots" class="slots-grid">
                                <div class="slot-empty">Select a follow-up date to see available time slots</div>
                            </div>
                            <input type="hidden" name="followup_start_time" id="followup_selected_time">
                            @error('followup_start_time')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Follow-up Concern -->
                        <div class="md:col-span-2">
                            <label for="followup_concern" class="section-title">Follow-up Concern *</label>
                            <textarea name="followup_concern" id="followup_concern" rows="3"
                                      class="textarea-field"
                                      placeholder="Brief description of what to discuss in the follow-up session...">{{ old('followup_concern', 'Follow-up session') }}</textarea>
                            @error('followup_concern')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Auto-approve option -->
                        <div class="md:col-span-2 flex items-center gap-3">
                            <input type="checkbox" name="auto_approve_followup" id="auto_approve_followup" value="1"
                                   {{ old('auto_approve_followup', true) ? 'checked' : '' }}
                                   class="custom-checkbox">
                            <label for="auto_approve_followup" class="custom-control-label font-medium text-[var(--text-primary)]">
                                Auto-approve this follow-up appointment
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Session Notes -->
                <div class="mt-6">
                    <label for="notes" class="section-title">Session Notes *</label>
                    <textarea name="notes" id="notes" rows="8"
                              class="textarea-field"
                              placeholder="Document the session details, topics discussed, observations, and any important insights..."
                              required>{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-[var(--text-muted)] mt-1">Minimum 10 characters required</p>
                </div>

                <!-- Follow-up Actions -->
                <div class="mt-6">
                    <label for="follow_up_actions" class="section-title">Follow-up Actions</label>
                    <textarea name="follow_up_actions" id="follow_up_actions" rows="4"
                              class="textarea-field"
                              placeholder="Any recommended actions, homework, or follow-up tasks for the student...">{{ old('follow_up_actions') }}</textarea>
                    @error('follow_up_actions')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recent Appointments -->
                @if($recentAppointments->isNotEmpty())
                    <div class="mt-6">
                        <label class="section-title">Recent Appointments with This Student</label>
                        <div class="recent-list">
                            @foreach($recentAppointments as $recentAppt)
                                <div class="recent-item">
                                    <strong>{{ $recentAppt->appointment_date->format('M j, Y') }}</strong>:
                                    {{ Str::limit($recentAppt->concern, 80) }}
                                    @if($recentAppt->sessionNotes->isNotEmpty())
                                        <span class="has-notes">
                                            <i class="fas fa-clipboard-check"></i> Has notes
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="mt-8 action-group flex flex-col md:flex-row gap-4 justify-end">
                    <a href="{{ route('counselor.session-notes.index', $student) }}"
                       class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit"
                            class="btn-primary">
                        <i class="fas fa-save mr-2 text-xs"></i> Save Session Notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Show/hide follow-up appointment scheduling based on checkbox
    document.getElementById('requires_follow_up').addEventListener('change', function() {
        const followupContainer = document.getElementById('followup_appointment_container');

        if (this.checked) {
            followupContainer.classList.remove('hidden');
            // Set default follow-up appointment date to 1 week from session date
            const sessionDate = document.getElementById('session_date').value;
            if (sessionDate) {
                const followupDate = new Date(sessionDate);
                followupDate.setDate(followupDate.getDate() + 7);
                document.getElementById('followup_appointment_date').value = followupDate.toISOString().split('T')[0];
                loadFollowupTimeSlots();
            }
        } else {
            followupContainer.classList.add('hidden');
        }
    });

    // Follow-up appointment scheduling functionality
    document.addEventListener('DOMContentLoaded', function() {
        const requiresFollowUpCheckbox = document.getElementById('requires_follow_up');
        const followupContainer = document.getElementById('followup_appointment_container');
        const followupDateInput = document.getElementById('followup_appointment_date');
        const followupTimeSlots = document.getElementById('followup_time_slots');
        const followupSelectedTime = document.getElementById('followup_selected_time');
        const counselorId = {{ Auth::user()->counselor->id }};
        let currentFollowupSelectedSlot = null;

        // Load time slots when follow-up date changes
        followupDateInput.addEventListener('change', loadFollowupTimeSlots);

        function loadFollowupTimeSlots() {
            const date = followupDateInput.value;

            if (!date) {
                followupTimeSlots.innerHTML = '<div class="slot-empty">Select a follow-up date to see available time slots</div>';
                followupSelectedTime.value = '';
                currentFollowupSelectedSlot = null;
                return;
            }

            // Show loading
            followupTimeSlots.innerHTML = '<div class="slot-empty">Loading available slots...</div>';
            followupSelectedTime.value = '';
            currentFollowupSelectedSlot = null;

            fetch(`/counselor/appointments/followup-available-slots?counselor_id=${counselorId}&date=${date}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Follow-up slots response:', data);

                    followupSelectedTime.value = '';
                    currentFollowupSelectedSlot = null;

                    if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                        followupTimeSlots.innerHTML = `
                            <div class="slot-error">
                                <p class="text-red-600 font-semibold text-sm">No working hours for this date</p>
                                <p class="text-red-500 text-xs mt-1">Please choose another date</p>
                            </div>
                        `;
                        return;
                    }

                    followupTimeSlots.innerHTML = '';

                    // Combine and sort all slots
                    const allSlots = [...data.available_slots, ...data.booked_slots].sort((a, b) =>
                        a.start.localeCompare(b.start)
                    );

                    // Create time slot buttons
                    allSlots.forEach(slot => {
                        const slotElement = document.createElement('button');
                        slotElement.type = 'button';
                        slotElement.dataset.start = slot.start;
                        slotElement.dataset.end = slot.end;
                        slotElement.dataset.status = slot.status;

                        if (slot.status === 'booked') {
                            // Booked slot
                            slotElement.className = 'time-slot booked';
                            slotElement.disabled = true;
                            slotElement.title = 'This time slot is already booked';
                            slotElement.innerHTML = `
                                <div class="font-medium line-through">${slot.display}</div>
                                <div class="text-red-500 mt-1">
                                    <i class="fas fa-lock text-xs"></i>
                                </div>
                            `;
                            slotElement.style.pointerEvents = 'none';
                        } else {
                            // Available slot
                            slotElement.className = 'time-slot available';
                            slotElement.innerHTML = `
                                <div class="font-medium">${slot.display}</div>
                                <div class="text-green-600 mt-1">
                                    <i class="fas fa-circle-check text-xs"></i>
                                </div>
                            `;

                            slotElement.addEventListener('click', function() {
                                // Remove selection from all available slots
                                followupTimeSlots.querySelectorAll('.time-slot.available').forEach(s => {
                                    s.classList.remove('selected');
                                    // Reset styles via class removal isn't enough if we added specific classes, 
                                    // but here we rely on toggling the 'selected' class which overrides defaults in CSS
                                    // However, to be safe with inline styles or specific overrides:
                                    s.classList.remove('selected'); 
                                });

                                // Select this slot
                                this.classList.add('selected');

                                followupSelectedTime.value = slot.start;
                                currentFollowupSelectedSlot = slot.start;

                                console.log('Selected follow-up time:', slot.start);
                            });
                        }

                        followupTimeSlots.appendChild(slotElement);
                    });

                    // Show message if all slots are booked
                    if (data.available_slots.length === 0 && data.booked_slots.length > 0) {
                        const message = document.createElement('div');
                        message.className = 'slot-msg';
                        message.innerHTML = `
                            <div class="text-yellow-700 mb-1">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <span class="font-semibold">All time slots are booked</span>
                            </div>
                            <p class="text-yellow-600">Please choose another date</p>
                        `;
                        followupTimeSlots.appendChild(message);
                    }

                })
                .catch(error => {
                    console.error('Error fetching follow-up time slots:', error);
                    followupTimeSlots.innerHTML = `
                        <div class="slot-error">
                            <p class="text-red-600 font-semibold text-sm">Error loading time slots</p>
                            <p class="text-red-500 text-xs mt-1">Please try again</p>
                        </div>
                    `;
                });
        }

        // Initialize if requires follow-up is already checked
        if (requiresFollowUpCheckbox.checked) {
            requiresFollowUpCheckbox.dispatchEvent(new Event('change'));
        }
    });

    // Auto-dismiss alerts (Generic handler for any alerts rendered by Laravel)
    document.addEventListener('DOMContentLoaded', function() {
        // Targeting standard Laravel flash message classes if they appear
        const alerts = document.querySelectorAll('.alert-success, .alert-error, .bg-green-100, .bg-red-100');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            }, 5000);
        });
    });
</script>
@endsection