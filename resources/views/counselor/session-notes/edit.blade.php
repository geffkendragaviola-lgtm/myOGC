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
    .session-date-badge { 
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--maroon-700); font-weight: 600; font-size: 0.8rem;
        background: rgba(122, 42, 42, 0.05); padding: 0.25rem 0.6rem;
        border-radius: 999px; margin-top: 0.5rem;
    }

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
                    <h1 class="text-xl sm:text-2xl font-bold">Edit Session Notes</h1>
                    <p class="font-medium mt-1">
                        for {{ $sessionNote->student->user->first_name }} {{ $sessionNote->student->user->last_name }}
                        <span class="text-[var(--text-muted)] font-normal">({{ $sessionNote->student->student_id }})</span>
                    </p>
                    <div class="student-meta">
                        {{ $sessionNote->student->college->name ?? 'N/A' }} • {{ $sessionNote->student->year_level }}
                    </div>
                    <div class="session-date-badge">
                        <i class="fas fa-calendar-days"></i>
                        Session Date: {{ $sessionNote->session_date->format('F j, Y') }}
                    </div>
                </div>
                <a href="{{ route('counselor.session-notes.index', $sessionNote->student) }}"
                   class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Notes
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="panel-card p-5 sm:p-6 md:p-8">
            <form action="{{ route('counselor.session-notes.update', $sessionNote) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    <!-- Session Date -->
                    <div>
                        <label for="session_date" class="section-title">Session Date *</label>
                        <input type="date" name="session_date" id="session_date"
                               value="{{ old('session_date', $sessionNote->session_date->format('Y-m-d')) }}"
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
                                <option value="{{ $value }}" {{ old('session_type', $sessionNote->session_type) == $value ? 'selected' : '' }}>
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
                                <option value="{{ $value }}" {{ old('mood_level', $sessionNote->mood_level) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('mood_level')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Session Notes -->
                <div class="mt-6">
                    <label for="notes" class="section-title">Session Notes *</label>
                    <textarea name="notes" id="notes" rows="8"
                              class="textarea-field"
                              placeholder="Document the session details, topics discussed, observations, and any important insights..."
                              required>{{ old('notes', $sessionNote->notes) }}</textarea>
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
                              placeholder="Any recommended actions, homework, or follow-up tasks for the student...">{{ old('follow_up_actions', $sessionNote->follow_up_actions) }}</textarea>
                    @error('follow_up_actions')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-8 action-group flex flex-col md:flex-row gap-4 justify-end">
                    <a href="{{ route('counselor.session-notes.index', $sessionNote->student) }}"
                       class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit"
                            class="btn-primary">
                        <i class="fas fa-save mr-2 text-xs"></i> Update Session Notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pre-populate follow-up appointment fields if they exist
    // Note: Since this is an EDIT form without the complex scheduling UI from the CREATE form,
    // this logic ensures data integrity if the form was extended in the future or if hidden fields were present.
    @if($sessionNote->requires_follow_up && $sessionNote->appointment)
        console.log('Pre-loaded follow-up data for appointment ID: {{ $sessionNote->appointment->id }}');
        // If you add the scheduling UI back to the edit page, uncomment and adapt the logic below:
        /*
        const requiresFollowUpCheckbox = document.getElementById('requires_follow_up');
        if(requiresFollowUpCheckbox) {
            requiresFollowUpCheckbox.checked = true;
            // Trigger change event to show container if using the create-page logic
            requiresFollowUpCheckbox.dispatchEvent(new Event('change'));
            
            const followupDateInput = document.getElementById('followup_appointment_date');
            if(followupDateInput) followupDateInput.value = '{{ $sessionNote->appointment->appointment_date->format('Y-m-d') }}';
            
            const followupConcernInput = document.getElementById('followup_concern');
            if(followupConcernInput) followupConcernInput.value = '{{ $sessionNote->appointment->concern }}';
            
            const followupSelectedTime = document.getElementById('followup_selected_time');
            if(followupSelectedTime) followupSelectedTime.value = '{{ $sessionNote->appointment->start_time }}';

            @if($sessionNote->appointment->status === 'approved')
            const autoApprove = document.getElementById('auto_approve_followup');
            if(autoApprove) autoApprove.checked = true;
            @endif
        }
        */
    @endif
});
</script>
@endsection