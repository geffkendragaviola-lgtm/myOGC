@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
    <div class="container mx-auto px-6 py-8 max-w-5xl">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Appointment Session</h1>
                    <p class="text-gray-600 mt-2">
                        {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                        ({{ $appointment->student->student_id }})
                    </p>
                    <p class="text-gray-500 text-sm">
                        {{ $appointment->student->college->name ?? 'N/A' }} • {{ $appointment->student->course }} • {{ $appointment->student->year_level }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <button type="button"
                            onclick="openFollowupModal()"
                            class="bg-[#F00000] text-white px-4 py-2 rounded-lg hover:bg-[#D40000] transition flex items-center">
                        <i class="fas fa-calendar-plus mr-2"></i> Book Follow-up
                    </button>
                    <a href="{{ route('counselor.appointments') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Appointments
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Appointment Details</h2>

                    <div class="space-y-3">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Date</div>
                            <div class="text-sm text-gray-900">{{ $appointment->appointment_date->format('F j, Y') }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Time</div>
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Category</div>
                            <div class="text-sm text-gray-900">{{ $appointment->status === 'referred' ? 'Referred' : 'Booked' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Case Number</div>
                            <div class="text-sm text-gray-900">{{ $appointment->case_number ?: '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Date of Referral</div>
                            <div class="text-sm text-gray-900">{{ $appointment->referral_requested_at ? $appointment->referral_requested_at->format('F j, Y g:i A') : '—' }}</div>
                        </div>
                    </div>
                </div>

                @if($followupAppointment)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Follow-up Appointment</h2>

                        <div class="space-y-3">
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Date</div>
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($followupAppointment->appointment_date)->format('F j, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Time</div>
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($followupAppointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($followupAppointment->end_time)->format('g:i A') }}
                                </div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Type of Booking</div>
                                <div class="text-sm text-gray-900">{{ $followupAppointment->booking_type ? ucwords(str_replace('_', ' ', $followupAppointment->booking_type)) : '—' }} - Follow up</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</div>
                                <div class="text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $followupAppointment->status)) }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Student Details</h2>

                    <div class="space-y-3">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Name</div>
                            <div class="text-sm text-gray-900">{{ $appointment->student->user->full_name }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Age</div>
                            <div class="text-sm text-gray-900">{{ $appointment->student->user->age ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Date of Birth</div>
                            <div class="text-sm text-gray-900">{{ $appointment->student->user->birthdate ? $appointment->student->user->birthdate->format('Y-m-d') : '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sex</div>
                            <div class="text-sm text-gray-900">{{ $appointment->student->user->sex ? ucfirst($appointment->student->user->sex) : '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Address</div>
                            <div class="text-sm text-gray-900 whitespace-pre-line">{{ $appointment->student->user->address ?: '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Phone Number</div>
                            <div class="text-sm text-gray-900">{{ $appointment->student->user->phone_number ?: '—' }}</div>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('counselor.students.profile', $appointment->student) }}"
                               class="inline-flex items-center text-[#F00000] hover:text-[#820000] text-sm">
                                <i class="fas fa-user mr-2"></i> View Student Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Session Notes</h2>

                    <form action="{{ route('counselor.appointments.session.store', $appointment) }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="appointment_type" class="block text-sm font-medium text-gray-700 mb-2">Type of Appointment *</label>
                                <select name="appointment_type"
                                        id="appointment_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent"
                                        required>
                                    <option value="">Select type</option>
                                    @foreach($appointmentTypeOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('appointment_type', $latestSessionNote->appointment_type ?? '') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('appointment_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Root Causes</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach($rootCauseOptions as $value => $label)
                                    <label class="flex items-center space-x-2 text-sm text-gray-700">
                                        <input type="checkbox"
                                               name="root_causes[]"
                                               value="{{ $value }}"
                                               class="rounded border-gray-300 text-[#F00000] focus:ring-[#F00000]"
                                               {{ in_array($value, old('root_causes', $latestSessionNote->root_causes ?? []), true) ? 'checked' : '' }}>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('root_causes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('root_causes.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Session Notes *</label>
                            <textarea name="notes"
                                      id="notes"
                                      rows="10"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent"
                                      required>{{ old('notes', $latestSessionNote->notes ?? '') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="follow_up_actions" class="block text-sm font-medium text-gray-700 mb-2">Assignment / Follow-up Actions</label>
                            <textarea name="follow_up_actions"
                                      id="follow_up_actions"
                                      rows="5"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent">{{ old('follow_up_actions', $latestSessionNote->follow_up_actions ?? '') }}</textarea>
                            @error('follow_up_actions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                    class="bg-[#F00000] text-white px-6 py-2 rounded-lg hover:bg-[#D40000] transition flex items-center">
                                <i class="fas fa-save mr-2"></i> Save Session Notes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="followupModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" onclick="handleFollowupBackdropClick(event)">
        <div class="bg-white rounded-xl shadow-2xl w-[700px] mx-4 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation();">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">Book Follow-up Appointment</h3>
                <button type="button" onclick="closeFollowupModal()" class="text-gray-500 hover:text-gray-700 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('counselor.appointments.followup.store', $appointment) }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="counselor_id" value="{{ $effectiveCounselorId }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="followup_booking_type" class="block text-sm font-medium text-gray-700 mb-2">Type of Booking *</label>
                        <select name="booking_type"
                                id="followup_booking_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent"
                                required>
                            <option value="">Choose a booking type</option>
                            <option value="Counseling" {{ old('booking_type') === 'Counseling' ? 'selected' : '' }}>Counseling</option>
                            <option value="Consultation" {{ old('booking_type') === 'Consultation' ? 'selected' : '' }}>Consultation</option>
                        </select>
                        @error('booking_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="followup_appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date"
                               name="appointment_date"
                               id="followup_appointment_date"
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time Slot *</label>
                        <div id="followup_time_slots" class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2 bg-white border border-gray-300 rounded-lg">
                            <div class="col-span-2 text-center text-gray-500 text-sm py-4">
                                Select a date to see available time slots
                            </div>
                        </div>
                        <input type="hidden" name="start_time" id="followup_selected_time">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="followup_concern" class="block text-sm font-medium text-gray-700 mb-2">Concern / Agenda *</label>
                    <textarea name="concern"
                              id="followup_concern"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent"
                              required>{{ old('concern', 'Follow-up session') }}</textarea>
                </div>

                <div class="mt-4 flex items-center">
                    <input type="checkbox"
                           name="auto_approve"
                           id="followup_auto_approve"
                           value="1"
                           checked
                           class="h-4 w-4 text-[#F00000] focus:ring-[#F00000] border-gray-300 rounded">
                    <label for="followup_auto_approve" class="ml-2 block text-sm text-gray-700">
                        Auto-approve this follow-up appointment
                    </label>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button"
                            onclick="closeFollowupModal()"
                            class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            id="followup_submit_btn"
                            class="bg-[#F00000] text-white px-6 py-2 rounded-lg hover:bg-[#D40000] transition disabled:opacity-50 disabled:cursor-not-allowed">
                        Book Follow-up
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openFollowupModal() {
            const modal = document.getElementById('followupModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
            updateFollowupSubmitState();
        }

        function closeFollowupModal() {
            const modal = document.getElementById('followupModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function handleFollowupBackdropClick(event) {
            if (event.target && event.target.id === 'followupModal') {
                closeFollowupModal();
            }
        }

        function updateFollowupSubmitState() {
            const btn = document.getElementById('followup_submit_btn');
            const selectedTime = document.getElementById('followup_selected_time');
            if (!btn || !selectedTime) {
                return;
            }
            btn.disabled = !selectedTime.value;
        }

        function setSelectedFollowupSlot(startTime, buttonEl) {
            const selectedTime = document.getElementById('followup_selected_time');
            if (!selectedTime) {
                return;
            }
            selectedTime.value = startTime;

            document.querySelectorAll('#followup_time_slots button[data-start]').forEach((btn) => {
                btn.classList.remove('bg-[#F00000]', 'text-white', 'border-[#820000]');
                btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
            });

            if (buttonEl) {
                buttonEl.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                buttonEl.classList.add('bg-[#F00000]', 'text-white', 'border-[#820000]');
            }
            updateFollowupSubmitState();
        }

        async function loadFollowupSlots() {
            const dateInput = document.getElementById('followup_appointment_date');
            const slotsContainer = document.getElementById('followup_time_slots');
            const selectedTime = document.getElementById('followup_selected_time');

            if (!dateInput || !slotsContainer || !selectedTime) {
                return;
            }

            selectedTime.value = '';
            updateFollowupSubmitState();

            if (!dateInput.value) {
                slotsContainer.innerHTML = '<div class="col-span-2 text-center text-gray-500 text-sm py-4">Select a date to see available time slots</div>';
                return;
            }

            slotsContainer.innerHTML = '<div class="col-span-2 text-center text-gray-500 text-sm py-4">Loading slots...</div>';

            const counselorId = {{ (int) $effectiveCounselorId }};
            const url = `{{ route('counselor.appointments.followup-available-slots') }}?counselor_id=${encodeURIComponent(counselorId)}&date=${encodeURIComponent(dateInput.value)}`;

            try {
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();

                const available = Array.isArray(data.available_slots) ? data.available_slots : [];
                if (!available.length) {
                    slotsContainer.innerHTML = `<div class="col-span-2 text-center text-gray-500 text-sm py-4">${data.message ? data.message : 'No available slots for this date'}</div>`;
                    return;
                }

                slotsContainer.innerHTML = '';
                available.forEach((slot) => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.dataset.start = slot.start;
                    btn.className = 'px-3 py-2 border rounded-lg text-sm bg-white text-gray-700 border-gray-300 hover:bg-gray-50 transition';
                    btn.textContent = slot.display || `${slot.start} - ${slot.end}`;
                    btn.addEventListener('click', () => setSelectedFollowupSlot(slot.start, btn));
                    slotsContainer.appendChild(btn);
                });
            } catch (e) {
                slotsContainer.innerHTML = '<div class="col-span-2 text-center text-red-600 text-sm py-4">Failed to load slots. Please try again.</div>';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('followup_appointment_date');
            if (dateInput) {
                dateInput.addEventListener('change', loadFollowupSlots);
            }
            updateFollowupSubmitState();
        });
    </script>
@endsection
