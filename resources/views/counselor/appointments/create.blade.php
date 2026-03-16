@extends('layouts.app')

@section('title', 'Book Appointment - OGC')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Book New Appointment</h1>
                <a href="{{ route('counselor.appointments') }}"
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Back
                </a>
            </div>

            <form id="appointmentForm" action="{{ route('counselor.appointments.store') }}" method="POST">
                @csrf

                <input type="hidden" name="counselor_id" id="counselorIdInput" value="{{ $selectedCounselor->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        @if($counselorAssignments->count() > 1)
                            <label class="block text-gray-700 font-semibold mb-2">College</label>
                            <select id="collegeSelect"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                @foreach($counselorAssignments as $assignment)
                                    <option value="{{ $assignment->id }}" {{ (int) $selectedCounselor->id === (int) $assignment->id ? 'selected' : '' }}>
                                        {{ $assignment->college->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        <label class="block text-gray-700 font-semibold mb-2 mt-{{ $counselorAssignments->count() > 1 ? '6' : '0' }}">Student</label>
                        <select name="student_id" id="studentSelect"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Choose a student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->student_id }} - {{ $student->user->first_name }} {{ $student->user->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-gray-700 font-semibold mb-2">Type of Booking</label>
                    <select name="booking_type" id="bookingType"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Choose a booking type</option>
                        <option value="Initial Interview" {{ old('booking_type') === 'Initial Interview' ? 'selected' : '' }}>Initial Interview</option>
                        <option value="Counseling" {{ old('booking_type') === 'Counseling' ? 'selected' : '' }}>Counseling</option>
                        <option value="Consultation" {{ old('booking_type') === 'Consultation' ? 'selected' : '' }}>Consultation</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500" id="bookingTypeHelp">Select the reason for the appointment.</p>
                    @error('booking_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label class="block text-gray-700 font-semibold mb-2">Select Date</label>
                    <div id="appointmentCalendar" class="border border-gray-200 rounded-xl bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <button type="button" id="calendarPrev"
                                    class="h-9 w-9 rounded-full border border-gray-200 text-gray-600 hover:bg-gray-50 transition">‹</button>
                            <h3 id="calendarMonthLabel" class="text-lg font-semibold text-gray-800"></h3>
                            <button type="button" id="calendarNext"
                                    class="h-9 w-9 rounded-full border border-gray-200 text-gray-600 hover:bg-gray-50 transition">›</button>
                        </div>
                        <div class="grid grid-cols-7 text-xs font-semibold text-gray-500 mb-2">
                            <span class="text-center">Sun</span>
                            <span class="text-center">Mon</span>
                            <span class="text-center">Tue</span>
                            <span class="text-center">Wed</span>
                            <span class="text-center">Thu</span>
                            <span class="text-center">Fri</span>
                            <span class="text-center">Sat</span>
                        </div>
                        <div id="calendarGrid" class="grid grid-cols-7 gap-2 text-sm"></div>
                        <p id="calendarStatus" class="mt-3 text-sm text-gray-500">Loading available dates...</p>
                    </div>
                    <input type="hidden" name="appointment_date" id="dateSelect" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    @error('appointment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label class="block text-gray-700 font-semibold mb-2">Available Time Slots</label>
                    <div id="timeSlots" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Select a counselor and date to see available time slots</div>
                    </div>
                    <input type="hidden" name="start_time" id="selectedTime" required>
                    @error('start_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label class="block text-gray-700 font-semibold mb-2">Concern / Agenda</label>
                    <textarea name="concern" rows="4"
                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              required>{{ old('concern') }}</textarea>
                    @error('concern')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('counselor.appointments') }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Book Now (Auto-Approved)</button>
                </div>
            </form>
        </div>
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
    const dateSelect = document.getElementById('dateSelect');
    const calendarGrid = document.getElementById('calendarGrid');
    const calendarMonthLabel = document.getElementById('calendarMonthLabel');
    const calendarPrev = document.getElementById('calendarPrev');
    const calendarNext = document.getElementById('calendarNext');
    const calendarStatus = document.getElementById('calendarStatus');
    const timeSlots = document.getElementById('timeSlots');
    const selectedTime = document.getElementById('selectedTime');

    let currentSelectedSlot = null;
    const minDate = new Date();
    minDate.setHours(0, 0, 0, 0);
    minDate.setDate(minDate.getDate() + 1);

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

        const yearLevel = student.year_level;
        const hasCompletedInitialInterview = student.initial_interview_completed === true;
        const hasInitialInterviewAppointment = initialInterviewBookedStudentIds
            .map(id => String(id))
            .includes(String(student.id));
        const hasInProgressInitialInterview = initialInterviewInProgressStudentIds
            .map(id => String(id))
            .includes(String(student.id));

        const isFirstYear = yearLevel === '1st Year';
        const isSecondYear = yearLevel === '2nd Year';
        const isUpperYear = !isFirstYear && !isSecondYear;

        const counselingOption = bookingTypeSelect.querySelector('option[value="Counseling"]');
        const consultationOption = bookingTypeSelect.querySelector('option[value="Consultation"]');
        const needsInitialInterview = isFirstYear || isSecondYear;

        if (hasCompletedInitialInterview) {
            if (bookingTypeSelect.value === 'Initial Interview') {
                bookingTypeSelect.value = '';
            }
            bookingTypeInitial.disabled = true;
            bookingTypeInitial.hidden = true;
            bookingTypeHelp.textContent = 'Initial Interview is already completed.';
            return;
        }

        if (hasInitialInterviewAppointment) {
            if (bookingTypeSelect.value === 'Initial Interview') {
                bookingTypeSelect.value = '';
            }
            bookingTypeInitial.disabled = true;
            bookingTypeInitial.hidden = true;

            if (needsInitialInterview) {
                counselingOption && (counselingOption.disabled = true);
                consultationOption && (consultationOption.disabled = true);
                bookingTypeHelp.textContent = 'Counseling and Consultation are available only after the Initial Interview is completed.';
                if (bookingTypeSelect.value === 'Counseling' || bookingTypeSelect.value === 'Consultation') {
                    bookingTypeSelect.value = '';
                }
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
                bookingTypeHelp.textContent = 'Counseling and Consultation are available only after the Initial Interview is completed.';
                return;
            }
        }

        if (needsInitialInterview && hasInProgressInitialInterview) {
            counselingOption && (counselingOption.disabled = true);
            consultationOption && (consultationOption.disabled = true);
            if (bookingTypeSelect.value === 'Counseling' || bookingTypeSelect.value === 'Consultation') {
                bookingTypeSelect.value = '';
            }
            bookingTypeHelp.textContent = 'Counseling and Consultation are available only after the Initial Interview is completed.';
            return;
        }

        if (isFirstYear) {
            bookingTypeInitial.disabled = false;
            bookingTypeInitial.hidden = false;
            bookingTypeSelect.value = 'Initial Interview';
            bookingTypeSelect.querySelectorAll('option').forEach(option => {
                option.disabled = option.value !== 'Initial Interview' && option.value !== '';
            });
            bookingTypeHelp.textContent = 'Initial Interview is required for 1st year students.';
            return;
        }

        if (isUpperYear) {
            if (bookingTypeSelect.value === 'Initial Interview') {
                bookingTypeSelect.value = '';
            }
            bookingTypeInitial.disabled = true;
            bookingTypeInitial.hidden = true;
            bookingTypeHelp.textContent = 'Initial Interview is not available for this student.';
            return;
        }

        bookingTypeInitial.disabled = false;
        bookingTypeInitial.hidden = false;
        bookingTypeHelp.textContent = 'Initial Interview is available if the student has not completed it yet.';
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
        calendarStatus.classList.remove('text-gray-500', 'text-green-600', 'text-red-600');
        if (tone === 'success') {
            calendarStatus.classList.add('text-green-600');
        } else if (tone === 'error') {
            calendarStatus.classList.add('text-red-600');
        } else {
            calendarStatus.classList.add('text-gray-500');
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
            const availabilityKnown = availabilityByDate.has(dateValue);
            const isAvailable = availabilityByDate.get(dateValue) === true;
            const isDisabled = !counselorId || isPast || !availabilityKnown || !isAvailable;

            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = day;
            button.disabled = isDisabled;
            button.className = 'h-10 w-10 md:h-11 md:w-11 rounded-lg border text-sm font-medium transition';

            if (isDisabled) {
                button.classList.add('border-transparent', 'text-gray-300', 'cursor-not-allowed');
            } else {
                button.classList.add('border-[#7c1d2a]/30', 'text-[#7c1d2a]', 'hover:bg-[#7c1d2a]/10');
            }

            if (selectedDate && isSameDay(selectedDate, date)) {
                button.classList.remove('border-[#7c1d2a]/30', 'text-[#7c1d2a]', 'hover:bg-[#7c1d2a]/10');
                button.classList.add('bg-[#7c1d2a]', 'text-white', 'border-[#7c1d2a]');
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
        availabilityByDate = new Map();
        renderCalendar();

        if (!counselorId) {
            setCalendarStatus('Unable to load availability. Please refresh the page.', 'error');
            return;
        }

        const requestId = ++availabilityRequestId;
        setCalendarStatus('Checking available dates...');
        const monthValue = `${currentMonth.getFullYear()}-${String(currentMonth.getMonth() + 1).padStart(2, '0')}`;

        try {
            const response = await fetch(`/appointments/available-dates?counselor_id=${counselorId}&month=${monthValue}`);
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
            renderCalendar();
            return;
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

    function loadAvailableSlots() {
        const counselorId = counselorIdInput.value;
        const date = dateSelect.value;

        if (!counselorId || !date) {
            timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Select a counselor and date to see available time slots</div>';
            selectedTime.value = '';
            return;
        }

        timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Loading available slots...</div>';

        fetch(`/appointments/available-slots?counselor_id=${counselorId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    timeSlots.innerHTML = `<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">${data.message}</div>`;
                    selectedTime.value = '';
                    return;
                }

                if (!Array.isArray(data.available_slots) || data.available_slots.length === 0) {
                    timeSlots.innerHTML = '<div class="text-yellow-700 text-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">No available time slots for this date. Please choose another date or counselor.</div>';
                    selectedTime.value = '';
                    return;
                }

                timeSlots.innerHTML = '';

                const availableSlots = [...data.available_slots].sort((a, b) => a.start.localeCompare(b.start));

                availableSlots.forEach(slot => {
                    const slotElement = document.createElement('button');
                    slotElement.type = 'button';
                    slotElement.className = 'time-slot p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer';
                    slotElement.textContent = slot.display;

                    slotElement.addEventListener('click', function() {
                        document.querySelectorAll('.time-slot').forEach(s => {
                            s.classList.remove('border-blue-500', 'bg-blue-100', 'text-blue-700');
                            s.classList.add('border-gray-200', 'text-gray-700');
                        });

                        this.classList.remove('border-gray-200', 'text-gray-700');
                        this.classList.add('border-blue-500', 'bg-blue-100', 'text-blue-700');

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
                timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">Error loading time slots. Please try again.</div>';
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

    studentSelect?.addEventListener('change', function() {
        updateBookingTypeOptions();
    });

    populateStudentsForAssignment(getSelectedAssignmentId());
    updateBookingTypeOptions();
    loadMonthAvailability();
    renderCalendar();
});
</script>
@endsection
