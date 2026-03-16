@extends('layouts.admin')

@section('title', 'Appointments - Admin Panel')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Appointments</h1>
                <p class="text-sm text-gray-600">All appointments in the system</p>
            </div>

            <div class="bg-white rounded-lg shadow px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Appointments This Month</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalAppointmentsThisMonth }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
            <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=all"
               class="bg-white rounded-lg shadow p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=pending"
               class="bg-white rounded-lg shadow p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=approved"
               class="bg-white rounded-lg shadow p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Approved</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved'] ?? 0 }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=completed"
               class="bg-white rounded-lg shadow p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-flag-checkered text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['completed'] ?? 0 }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=rejected"
               class="bg-white rounded-lg shadow p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rejected</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-5 border-b border-gray-200">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Case #, student, counselor, concern"
                               class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Case #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Counselor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date &amp; Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($appointments as $appointment)
                            <tr class="hover:bg-blue-50 transition cursor-pointer" onclick="showAppointmentDetails({{ $appointment->id }})">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->case_number ?? ('#' . $appointment->id) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $appointment->student->user->first_name ?? 'N/A' }} {{ $appointment->student->user->last_name ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $appointment->student->student_id ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $appointment->counselor->user->first_name ?? 'N/A' }} {{ $appointment->counselor->user->last_name ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $appointment->counselor->college->name ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appointment->booking_type ?? '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusLabels = [
                                            'reschedule_requested' => 'Reschedule Requested (Pending Student Approval)',
                                            'reschedule_rejected' => 'Rejected by Student',
                                            'rescheduled' => 'Scheduled (Rescheduled)',
                                        ];

                                        $statusDisplay = $statusLabels[$appointment->status] ?? ucfirst(str_replace('_', ' ', $appointment->status));

                                        $referralOutcomeDisplay = null;
                                        if ($appointment->referral_outcome) {
                                            $referralOutcomeDisplay = ucfirst(str_replace('_', ' ', $appointment->referral_outcome));
                                        }

                                        if ($appointment->is_referred) {
                                            $originalName = ($appointment->originalCounselor && $appointment->originalCounselor->user)
                                                ? ($appointment->originalCounselor->user->first_name . ' ' . $appointment->originalCounselor->user->last_name)
                                                : 'Unknown Counselor';
                                            $referredName = ($appointment->referredCounselor && $appointment->referredCounselor->user)
                                                ? ($appointment->referredCounselor->user->first_name . ' ' . $appointment->referredCounselor->user->last_name)
                                                : 'Unknown Counselor';

                                            $suffix = '';
                                            if ($appointment->referral_previous_status === 'rescheduled') {
                                                $suffix .= ' (Rescheduled)';
                                            }

                                            $statusDisplay = "Referred from {$originalName} to {$referredName}{$suffix}";
                                        }
                                    @endphp

                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                               ($appointment->status === 'approved' ? 'bg-green-100 text-green-800' :
                                               ($appointment->status === 'rejected' ? 'bg-red-100 text-red-800' :
                                               ($appointment->status === 'completed' ? 'bg-blue-100 text-blue-800' :
                                               ($appointment->status === 'referred' ? 'bg-purple-100 text-purple-800' :
                                               'bg-gray-100 text-gray-800')))) }}">
                                            {{ $statusDisplay }}
                                        </span>

                                        @if($appointment->is_referred && $referralOutcomeDisplay)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $appointment->referral_outcome === 'approved' ? 'bg-green-100 text-green-800' :
                                                   ($appointment->referral_outcome === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ $referralOutcomeDisplay }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-sm text-gray-500" colspan="6">No appointments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $appointments->appends(request()->query())->links() }}
            </div>
        </div>

        <div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800">Appointment Details</h3>
                        <button onclick="closeAppointmentModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div id="appointmentDetails" class="p-6">
                </div>
            </div>
        </div>
    </div>

    <script>
        function showAppointmentDetails(appointmentId) {
            fetch(`/admin/appointments/${appointmentId}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const modal = document.getElementById('appointmentModal');
                    const details = document.getElementById('appointmentDetails');

                    details.innerHTML = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Student Name</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.student?.user?.first_name || 'N/A'} ${data.student?.user?.last_name || ''}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Student ID</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.student?.student_id || 'N/A'}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">College</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.student?.college?.name || 'N/A'}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Year Level</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.student?.year_level || 'N/A'}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Counselor</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.counselor?.name || 'N/A'}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Counselor College</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.counselor?.college?.name || 'N/A'}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.formatted_date || 'N/A'}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Time</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.formatted_time || 'N/A'}</p>
                                </div>
                            </div>

                            ${(data.appointment?.status === 'referred' && data.formatted_proposed_date && data.formatted_proposed_time) ? `
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-purple-700">Proposed Date</label>
                                    <p class="mt-1 text-sm text-purple-900">${data.formatted_proposed_date}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-purple-700">Proposed Time</label>
                                    <p class="mt-1 text-sm text-purple-900">${data.formatted_proposed_time}</p>
                                </div>
                            </div>
                            ` : ''}

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type of Booking</label>
                                <p class="mt-1 text-sm text-gray-900">${data.appointment?.booking_type || 'N/A'}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Concern</label>
                                <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">${data.appointment?.concern || 'N/A'}</p>
                            </div>

                            ${data.appointment?.notes ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Counselor Notes</label>
                                <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">${data.appointment.notes}</p>
                            </div>
                            ` : ''}

                            ${(data.appointment?.is_referred || data.appointment?.referral_reason || data.referral?.referred_to_name || data.referral?.referred_from_name) ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Referral Details</label>
                                <div class="mt-1 p-3 rounded-lg border border-purple-200 bg-purple-50 space-y-1">
                                    ${data.referral?.referred_from_name ? `
                                    <p class="text-sm text-purple-900"><span class="font-medium">Referred from:</span> ${data.referral.referred_from_name}</p>
                                    ` : ''}
                                    ${data.referral?.referred_to_name ? `
                                    <p class="text-sm text-purple-900"><span class="font-medium">Referred to:</span> ${data.referral.referred_to_name}</p>
                                    ` : ''}
                                    ${data.formatted_referral_date ? `
                                    <p class="text-sm text-purple-900"><span class="font-medium">Referral date:</span> ${data.formatted_referral_date}</p>
                                    ` : ''}
                                    ${data.appointment?.referral_reason ? `
                                    <div class="pt-2">
                                        <p class="text-xs font-medium text-purple-800">Reason:</p>
                                        <p class="text-sm text-purple-900 whitespace-pre-line">${data.appointment.referral_reason}</p>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                            ` : ''}

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        ${data.appointment?.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                        data.appointment?.status === 'approved' ? 'bg-green-100 text-green-800' :
                                        data.appointment?.status === 'rejected' ? 'bg-red-100 text-red-800' :
                                        data.appointment?.status === 'referred' ? 'bg-purple-100 text-purple-800' :
                                        data.appointment?.status === 'completed' ? 'bg-blue-100 text-blue-800' :
                                        'bg-gray-100 text-gray-800'}">
                                        ${data.appointment?.status_display || 'N/A'}
                                    </span>

                                    ${(data.appointment?.is_referred && data.appointment?.referral_outcome_display) ? `
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        ${data.appointment?.referral_outcome === 'approved' ? 'bg-green-100 text-green-800' :
                                        data.appointment?.referral_outcome === 'rejected' ? 'bg-red-100 text-red-800' :
                                        'bg-gray-100 text-gray-800'}">
                                        ${data.appointment.referral_outcome_display}
                                    </span>
                                    ` : ''}
                                </div>
                            </div>

                            ${data.appointment?.has_session_notes ? `
                            <div class="border-t pt-4 mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Session Notes</h4>
                                <div class="space-y-3">
                                    ${(data.session_notes || []).map(note => `
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="text-xs font-medium text-gray-600">
                                                    ${note.session_date} • ${note.session_type_label}
                                                </span>
                                                ${note.mood_level ? `
                                                <span class="text-xs px-2 py-1 rounded-full
                                                    ${note.mood_level === 'very_good' ? 'bg-green-100 text-green-800' :
                                                    note.mood_level === 'good' ? 'bg-blue-100 text-blue-800' :
                                                    note.mood_level === 'neutral' ? 'bg-yellow-100 text-yellow-800' :
                                                    note.mood_level === 'low' ? 'bg-orange-100 text-orange-800' :
                                                    'bg-red-100 text-red-800'}">
                                                    ${note.mood_level_label}
                                                </span>
                                                ` : ''}
                                            </div>
                                            <p class="text-sm text-gray-700 whitespace-pre-line">${note.notes}</p>
                                            ${note.follow_up_actions ? `
                                            <div class="mt-2">
                                                <p class="text-xs font-medium text-gray-600">Follow-up:</p>
                                                <p class="text-sm text-gray-700 whitespace-pre-line">${note.follow_up_actions}</p>
                                            </div>
                                            ` : ''}
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                            ` : ''}

                            <div class="border-t pt-4 mt-4 flex justify-end">
                                <div class="flex gap-2">
                                    ${data.appointment?.session_notes_url && data.appointment?.has_session_notes ? `
                                    <a href="${data.appointment.session_notes_url}"
                                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm">
                                        <i class="fas fa-clipboard mr-2"></i> View Session Notes
                                    </a>
                                    ` : ''}
                                    ${data.student?.profile_url ? `
                                    <a href="${data.student.profile_url}"
                                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                                        <i class="fas fa-user mr-2"></i> View Student Details
                                    </a>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;

                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching appointment details:', error);
                    const modal = document.getElementById('appointmentModal');
                    const details = document.getElementById('appointmentDetails');
                    details.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-4xl text-red-300 mb-4"></i>
                            <p class="text-red-500">Error loading appointment details. Please try again.</p>
                        </div>
                    `;
                    modal.classList.remove('hidden');
                });
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAppointmentModal();
            }
        });
    </script>
@endsection
