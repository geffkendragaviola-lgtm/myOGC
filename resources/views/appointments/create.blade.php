@extends('layouts.student')

@section('title', 'Book Appointment - OGC')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Book an Appointment</h1>

            <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST">
                @csrf

                <!-- Counselor Selection -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Counselor</label>

                    <!-- Counselor Type Selection -->
                    <div class="mb-4" id="counselorTypeWrapper">
                        <label class="inline-flex items-center">
                            <input type="radio" name="counselor_type" value="college" checked
                                   class="counselor-type-radio text-blue-600 focus:ring-blue-500">
                            <span class="ml-2">{{ ($allowAllCounselors ?? false) ? 'Counselors from all colleges' : 'Counselors from my college' }}</span>
                        </label>
                        <label class="inline-flex items-center ml-6" id="referredCounselorOption">
                            <input type="radio" name="counselor_type" value="referred"
                                   class="counselor-type-radio text-blue-600 focus:ring-blue-500">
                            <span class="ml-2">Previously referred counselors</span>
                        </label>
                    </div>

                    <select name="counselor_id" id="counselorSelect"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Choose a counselor</option>
                        <!-- Options will be populated by JavaScript -->
                    </select>
                    <input type="hidden" name="counselor_id" id="counselorAutoAssignedInput">
                    <p id="counselorAutoAssigned" class="hidden mt-2 text-sm text-gray-600"></p>

                    <!-- Loading indicator -->
                    <div id="counselorLoading" class="hidden mt-2 text-blue-600">
                        Loading counselors...
                    </div>
                </div>

                <!-- Booking Type -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Type of Booking</label>
                    <select name="booking_type" id="bookingType"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Choose a booking type</option>
                        <option value="Initial Interview" id="bookingTypeInitial">Initial Interview</option>
                        <option value="Counseling">Counseling</option>
                        <option value="Consultation">Consultation</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500" id="bookingTypeHelp">
                        Select the reason for your appointment.
                    </p>
                </div>

                <!-- Rest of the form remains the same -->
                <!-- Date Selection -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Select Date</label>
                    <div id="appointmentCalendar" class="border border-gray-200 rounded-xl bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <button type="button" id="calendarPrev"
                                    class="h-9 w-9 rounded-full border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                ‹
                            </button>
                            <h3 id="calendarMonthLabel" class="text-lg font-semibold text-gray-800"></h3>
                            <button type="button" id="calendarNext"
                                    class="h-9 w-9 rounded-full border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                ›
                            </button>
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
                        <p id="calendarStatus" class="mt-3 text-sm text-gray-500">
                            Select a counselor to load available dates.
                        </p>
                    </div>
                    <input type="hidden" name="appointment_date" id="dateSelect"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                </div>

                <!-- Time Slots -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Available Time Slots</label>
                    <div id="timeSlots" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">
                            Select a counselor and date to see available time slots
                        </div>
                    </div>
                    <input type="hidden" name="start_time" id="selectedTime" required>
                </div>

                <!-- Concern -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Presenting Problem</label>
                    <textarea name="concern" rows="4"
                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder=" Reason for booking for appointment " required></textarea>
                </div>

                <!-- Informed Consent -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Informed Consent for Counseling (Terms and Agreement)
                    </label>
                    <div class="flex items-center gap-3">
                        <button type="button" id="openConsentModal"
                                class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition border border-blue-200">
                            Read Informed Consent
                        </button>
                    </div>
                    <p id="consentHint" class="mt-2 text-sm text-gray-500">
                        Please read the informed consent to enable the checkbox.
                    </p>
                </div>

                <!-- Informed Consent Modal -->
                <div id="consentModal" class="fixed inset-0 z-50 hidden">
                    <div class="absolute inset-0 bg-gray-900/60" data-consent-close></div>
                    <div class="relative mx-auto mt-10 w-11/12 max-w-3xl bg-white rounded-xl shadow-lg">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">INFORMED CONSENT FOR COUNSELING</h2>
                            <button type="button" class="text-gray-500 hover:text-gray-700" data-consent-close>
                                ✕
                            </button>
                        </div>
                        <div id="consentContent" class="px-6 py-4 max-h-[70vh] overflow-y-auto text-gray-700 space-y-4">
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
                            <h3 class="text-lg font-semibold text-gray-800">CONFIDENTIALITY</h3>
                            <p>
                                All interactions with the counseling services of the Office of Guidance and Counseling (OGC), including scheduling
                                of or attendance at appointments, content of your sessions, progress in counseling, and your records are confidential.
                                No record of counseling is contained in any academic, educational or job placement file. You may request in writing
                                that the counselor releases specific information about your counseling to persons you designate.
                            </p>
                            <h3 class="text-lg font-semibold text-gray-800">EXCEPTIONS TO CONFIDENTIALITY</h3>
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
                            <h3 class="text-lg font-semibold text-gray-800">CLIENT'S ROLES</h3>
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
                            <h3 class="text-lg font-semibold text-gray-800">REFERRAL TO EXPERTS</h3>
                            <p>
                                If you are referred off campus to health, mental health or substance abuse professionals, you are responsible for their charges,
                                except when referred to clinicians who have memorandum of understanding/MOU with the Institute.
                            </p>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between gap-4">
                            <label class="inline-flex items-center text-gray-700">
                                <input type="checkbox" name="consent_acknowledged" id="consentAcknowledged"
                                       class="text-blue-600 focus:ring-blue-500" required disabled>
                                <span class="ml-2">I have read and understood the Informed Consent for Counseling.</span>
                            </label>
                            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                    data-consent-close>
                                Close
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('appointments.index') }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            id="submitAppointment"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        Book Appointment
                    </button>
                </div>
                <p id="submitHint" class="mt-2 text-sm text-right text-gray-500">
                    Please read and acknowledge the informed consent to enable booking.
                </p>
            </form>
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

    const studentYearLevel = {!! json_encode(optional(auth()->user()->student)->year_level) !!};
    const studentInitialInterviewCompleted = {!! json_encode(optional(auth()->user()->student)->initial_interview_completed) !!};
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
        if (!calendarStatus) {
            return;
        }
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
        if (!calendarGrid || !calendarMonthLabel) {
            return;
        }

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
                if (button.disabled) {
                    return;
                }
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
            if (requestId !== availabilityRequestId) {
                return;
            }
            const availability = data.availability || {};
            Object.keys(availability).forEach(dateValue => {
                availabilityByDate.set(dateValue, availability[dateValue] === true);
            });
        } catch (error) {
            if (requestId !== availabilityRequestId) {
                return;
            }
        }

        if (requestId !== availabilityRequestId) {
            return;
        }

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

    // Load counselors based on type selection
    function loadCounselors(type) {
        counselorSelect.innerHTML = '<option value="">Choose a counselor</option>';
        counselorLoading.classList.remove('hidden');

        if (type === 'college') {
            // Use pre-loaded college counselors
            populateCounselorSelect(collegeCounselors);
            counselorLoading.classList.add('hidden');
        } else {
            // Load referred counselors via AJAX
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
            .catch(() => {
                // Keep the default college counselor flow if the request fails.
            });
    }

    // Load time slots function
    function loadAvailableSlots() {
        const counselorId = getActiveCounselorId();
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

                // ... existing time slot loading logic ...
                if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                    timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">No working hours for this date. Please choose another date.</div>';
                    selectedTime.value = '';
                    return;
                }

                timeSlots.innerHTML = '';

                const availableSlots = [...data.available_slots].sort((a, b) =>
                    a.start.localeCompare(b.start)
                );

                if (availableSlots.length === 0) {
                    timeSlots.innerHTML = '<div class="text-yellow-700 text-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">No available time slots for this date. Please choose another date or counselor.</div>';
                    selectedTime.value = '';
                    return;
                }

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
            .catch(error => {
                console.error('Error:', error);
                timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">Error loading time slots. Please try again.</div>';
            });
    }

    // Event listeners
    counselorTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                loadCounselors(this.value);
                // Reset time slots when counselor type changes
                timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Select a counselor and date to see available time slots</div>';
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

    // Load initial counselors
    loadCounselors('college');

    updateBookingTypeOptions();
    checkReferredCounselorsAvailability();
    renderCalendar();

    calendarPrev?.addEventListener('click', function() {
        const prevMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() - 1, 1);
        const minMonth = new Date(minDate.getFullYear(), minDate.getMonth(), 1);
        if (prevMonth < minMonth) {
            return;
        }
        currentMonth = prevMonth;
        loadMonthAvailability();
    });

    calendarNext?.addEventListener('click', function() {
        currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 1);
        loadMonthAvailability();
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appointmentForm = document.getElementById('appointmentForm');
    const submitAppointment = document.getElementById('submitAppointment');
    const submitHint = document.getElementById('submitHint');
    const consentModal = document.getElementById('consentModal');
    const openConsentModal = document.getElementById('openConsentModal');
    const consentCloseButtons = consentModal?.querySelectorAll('[data-consent-close]') || [];
    const consentContent = document.getElementById('consentContent');
    const consentCheckbox = document.getElementById('consentAcknowledged');
    const consentHint = document.getElementById('consentHint');
    let consentScrolledToBottom = false;

    function openModal() {
        consentModal?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        if (consentContent) {
            consentContent.scrollTop = 0;
        }
        consentScrolledToBottom = false;
        if (consentCheckbox) {
            consentCheckbox.checked = false;
            consentCheckbox.disabled = true;
        }
        if (submitAppointment) {
            submitAppointment.disabled = true;
        }
        if (submitHint) {
            submitHint.textContent = 'Please read and acknowledge the informed consent to enable booking.';
            submitHint.classList.remove('text-green-600');
            submitHint.classList.add('text-gray-500');
        }
        if (consentHint) {
            consentHint.textContent = 'Scroll to the bottom to enable the checkbox.';
            consentHint.classList.remove('text-green-600');
            consentHint.classList.add('text-gray-500');
        }
    }

    function closeModal() {
        consentModal?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    openConsentModal?.addEventListener('click', openModal);
    consentCloseButtons.forEach(button => button.addEventListener('click', closeModal));
    consentContent?.addEventListener('scroll', function() {
        if (consentScrolledToBottom) {
            return;
        }
        const nearBottom = consentContent.scrollTop + consentContent.clientHeight >= consentContent.scrollHeight - 5;
        if (nearBottom) {
            consentScrolledToBottom = true;
            if (consentCheckbox) {
                consentCheckbox.disabled = false;
            }
            if (consentHint) {
                consentHint.textContent = 'You can now acknowledge after reading.';
                consentHint.classList.remove('text-gray-500');
                consentHint.classList.add('text-green-600');
            }
        }
    });

    consentCheckbox?.addEventListener('change', function() {
        if (submitAppointment) {
            submitAppointment.disabled = !this.checked;
        }
        if (submitHint) {
            if (this.checked) {
                submitHint.textContent = 'Booking enabled.';
                submitHint.classList.remove('text-gray-500');
                submitHint.classList.add('text-green-600');
            } else {
                submitHint.textContent = 'Please read and acknowledge the informed consent to enable booking.';
                submitHint.classList.remove('text-green-600');
                submitHint.classList.add('text-gray-500');
            }
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !consentModal?.classList.contains('hidden')) {
            closeModal();
        }
    });

    appointmentForm?.addEventListener('submit', function(event) {
        if (!consentCheckbox || !consentCheckbox.checked) {
            event.preventDefault();
            consentCheckbox?.focus();
            if (consentHint) {
                consentHint.textContent = 'You must read and acknowledge the informed consent to continue.';
                consentHint.classList.remove('text-green-600');
                consentHint.classList.add('text-red-600');
            }
        }
    });
});
</script>
@endsection
