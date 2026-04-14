@extends('layouts.admin')

@section('title', 'Appointments - Admin Panel')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Modern Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-[#F00000]/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-check text-[#F00000] text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Appointments</h1>
                            <p class="text-sm text-gray-500">Manage and track all counseling appointments</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Card - Compact & Modern -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-5 py-3 flex items-center gap-4">
                    <div class="p-2.5 bg-gradient-to-br from-[#FFF9E6] to-[#FFE100]/20 rounded-xl">
                        <i class="fas fa-calendar-week text-[#F8650C] text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">This Month</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalAppointmentsThisMonth }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Stats Cards Grid -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=all"
               class="group bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 block">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center group-hover:bg-gray-200 transition">
                        <i class="fas fa-chart-simple text-gray-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Total</p>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=pending"
               class="group bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 block">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#FFF9E6] rounded-xl flex items-center justify-center group-hover:bg-[#FFE100]/30 transition">
                        <i class="fas fa-hourglass-half text-[#FFC917] text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Pending</p>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=approved"
               class="group bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 block">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#FFF9E6] rounded-xl flex items-center justify-center group-hover:bg-[#FFE100]/30 transition">
                        <i class="fas fa-check-circle text-[#F8650C] text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Approved</p>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['approved'] ?? 0 }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=completed"
               class="group bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 block">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center group-hover:bg-gray-200 transition">
                        <i class="fas fa-flag-checkered text-gray-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Completed</p>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['completed'] ?? 0 }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=rejected"
               class="group bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 block">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#FFF0F0] rounded-xl flex items-center justify-center group-hover:bg-red-100 transition">
                        <i class="fas fa-times-circle text-[#820000] text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Rejected</p>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Modern Filter Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <i class="fas fa-sliders-h text-[#F00000] text-sm"></i>
                    <span class="text-sm font-medium text-gray-700">Filter Appointments</span>
                </div>
            </div>
            <div class="p-5">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Search</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text"
                                   name="search"
                                   value="{{ $search }}"
                                   placeholder="Case #, student, counselor..."
                                   class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:border-[#F00000] focus:ring-1 focus:ring-[#F00000] transition outline-none" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:border-[#F00000] focus:ring-1 focus:ring-[#F00000] transition outline-none bg-white">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Session Notes</label>
                        <select name="has_session_notes" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:border-[#F00000] focus:ring-1 focus:ring-[#F00000] transition outline-none bg-white">
                            <option value="">All Appointments</option>
                            <option value="yes" {{ request('has_session_notes') === 'yes' ? 'selected' : '' }}>Has Session Notes</option>
                            <option value="no" {{ request('has_session_notes') === 'no' ? 'selected' : '' }}>No Session Notes</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full px-4 py-2.5 bg-[#F00000] text-white rounded-xl hover:bg-[#D40000] transition text-sm font-medium">
                            <i class="fas fa-search mr-2"></i>Apply
                        </button>
                        <a href="{{ route('admin.appointments') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition text-sm font-medium">
                            <i class="fas fa-undo-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modern Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-5 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Case #</span>
                            </th>
                            <th class="px-5 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</span>
                            </th>
                            <th class="px-5 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Counselor</span>
                            </th>
                            <th class="px-5 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Schedule</span>
                            </th>
                            <th class="px-5 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</span>
                            </th>
                            <th class="px-5 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</span>
                            </th>
                            <th class="px-5 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Notes</span>
                            </th>
                            <th class="px-5 py-4 text-center">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($appointments as $appointment)
                            @php
                                $hasNotes = $appointment->sessionNotes->count() > 0;
                                $notesCount = $appointment->sessionNotes->count();
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition group">
                                <td class="px-5 py-4">
                                    <span class="text-sm font-mono font-medium text-gray-900">{{ $appointment->case_number ?? ('#' . $appointment->id) }}</span>
                                 </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-[#F00000]/10 to-[#820000]/10 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-graduate text-[#F00000] text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $appointment->student->user->first_name ?? 'N/A' }} {{ $appointment->student->user->last_name ?? '' }}</p>
                                            <p class="text-xs text-gray-400">{{ $appointment->student->student_id ?? 'No ID' }}</p>
                                        </div>
                                    </div>
                                 </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-tie text-gray-500 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $appointment->counselor->user->first_name ?? 'N/A' }} {{ $appointment->counselor->user->last_name ?? '' }}</p>
                                            <p class="text-xs text-gray-400">{{ $appointment->counselor->college->name ?? '' }}</p>
                                        </div>
                                    </div>
                                 </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</span>
                                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}</span>
                                    </div>
                                 </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600">
                                        {{ $appointment->booking_type ?? 'Regular' }}
                                    </span>
                                 </td>
                                <td class="px-5 py-4">
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

                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full
                                            {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                               ($appointment->status === 'approved' ? 'bg-green-100 text-green-700' :
                                               ($appointment->status === 'rejected' ? 'bg-red-100 text-red-700' :
                                               ($appointment->status === 'completed' ? 'bg-gray-100 text-[#820000]' :
                                               ($appointment->status === 'referred' ? 'bg-[#FFF9E6] text-[#820000]' :
                                               'bg-gray-100 text-gray-600')))) }}">
                                            {{ $statusDisplay }}
                                        </span>

                                        @if($appointment->is_referred && $referralOutcomeDisplay)
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full
                                                {{ $appointment->referral_outcome === 'approved' ? 'bg-green-100 text-green-700' :
                                                   ($appointment->referral_outcome === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                                                {{ $referralOutcomeDisplay }}
                                            </span>
                                        @endif
                                    </div>
                                 </td>
                                <td class="px-5 py-4">
                                    @if($hasNotes)
                                        <div class="flex items-center gap-1.5 group">
                                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                            <span class="text-sm font-medium text-green-600">{{ $notesCount }} note(s)</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5">
                                            <i class="fas fa-clipboard text-gray-300 text-sm"></i>
                                            <span class="text-sm text-gray-400">No notes</span>
                                        </div>
                                    @endif
                                 </td>
                                <td class="px-5 py-4 text-center">
                                    <button onclick="showAppointmentDetails({{ $appointment->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#820000]/10 text-[#820000] rounded-lg hover:bg-[#820000] hover:text-white transition-all duration-200 text-sm font-medium">
                                        <i class="fas fa-eye text-xs"></i>
                                        <span>View</span>
                                    </button>
                                 </td>
                             </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">No appointments found</p>
                                        <p class="text-sm text-gray-400">Try adjusting your search or filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modern Pagination -->
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $appointments->appends(request()->query())->links() }}
            </div>
        </div>

        <!-- Modern Modal -->
        <div id="appointmentModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-all duration-200">
            <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto animate-fade-in-up">
                <div class="sticky top-0 bg-white rounded-t-2xl px-6 py-5 border-b border-gray-100 z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#F00000]/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar-check text-[#F00000] text-lg"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Appointment Details</h3>
                        </div>
                        <button onclick="closeAppointmentModal()" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-200 transition">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
                <div id="appointmentDetails" class="p-6">
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-12 h-12 border-4 border-gray-200 border-t-[#F00000] rounded-full animate-spin"></div>
                        <p class="mt-4 text-gray-500">Loading appointment details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.3s ease-out;
        }
        
        /* Custom scrollbar for modal */
        #appointmentModal .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }
        #appointmentModal .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        #appointmentModal .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        #appointmentModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Table hover effect */
        tbody tr {
            transition: all 0.2s ease;
        }
    </style>

    <script>
        function showAppointmentDetails(appointmentId) {
            const modal = document.getElementById('appointmentModal');
            const details = document.getElementById('appointmentDetails');
            
            details.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="w-12 h-12 border-4 border-gray-200 border-t-[#F00000] rounded-full animate-spin"></div>
                    <p class="mt-4 text-gray-500">Loading appointment details...</p>
                </div>
            `;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            fetch(`/admin/appointments/${appointmentId}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    let sessionNotesHtml = '';
                    if (data.session_notes && data.session_notes.length > 0) {
                        sessionNotesHtml = `
                            <div class="border-t border-gray-100 pt-5 mt-4">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-clipboard-list text-green-600 text-sm"></i>
                                    </div>
                                    <h4 class="text-base font-semibold text-gray-800">Session Notes (${data.session_notes.length})</h4>
                                </div>
                                <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                                    ${data.session_notes.map(note => `
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-gray-200 transition">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <span class="text-sm font-semibold text-gray-800">
                                                        ${note.session_date}
                                                    </span>
                                                    <span class="text-xs text-gray-500 ml-2">
                                                        • ${note.session_type_label}
                                                    </span>
                                                </div>
                                                ${note.mood_level ? `
                                                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                                    ${note.mood_level === 'very_good' ? 'bg-green-100 text-green-700' :
                                                    note.mood_level === 'good' ? 'bg-blue-100 text-blue-700' :
                                                    note.mood_level === 'neutral' ? 'bg-yellow-100 text-yellow-700' :
                                                    note.mood_level === 'low' ? 'bg-orange-100 text-orange-700' :
                                                    'bg-red-100 text-red-700'}">
                                                    <i class="fas fa-face-smile mr-1 text-xs"></i> ${note.mood_level_label}
                                                </span>
                                                ` : ''}
                                            </div>
                                            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">${note.notes}</p>
                                            ${note.follow_up_actions ? `
                                            <div class="mt-3 pt-3 border-t border-gray-100">
                                                <p class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Follow-up Actions</p>
                                                <p class="text-sm text-gray-600 whitespace-pre-line">${note.follow_up_actions}</p>
                                                ${note.next_session_date ? `
                                                <p class="text-xs text-gray-400 mt-2">
                                                    <i class="fas fa-calendar-alt mr-1"></i> Next Session: ${note.next_session_date}
                                                </p>
                                                ` : ''}
                                            </div>
                                            ` : ''}
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    } else {
                        sessionNotesHtml = `
                            <div class="border-t border-gray-100 pt-5 mt-4">
                                <div class="bg-gray-50 rounded-xl p-6 text-center">
                                    <i class="fas fa-clipboard-list text-gray-300 text-3xl mb-2"></i>
                                    <p class="text-sm text-gray-500">No session notes available for this appointment.</p>
                                </div>
                            </div>
                        `;
                    }

                    details.innerHTML = `
                        <div class="space-y-5">
                            <!-- Student & Counselor Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gradient-to-br from-[#F00000]/5 to-transparent rounded-xl p-4 border border-gray-100">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 bg-[#F00000]/10 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user-graduate text-[#F00000] text-sm"></i>
                                        </div>
                                        <h4 class="text-sm font-semibold text-gray-700">Student Information</h4>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-sm"><span class="font-medium text-gray-600">Name:</span> <span class="text-gray-800">${data.student?.user?.first_name || 'N/A'} ${data.student?.user?.last_name || ''}</span></p>
                                        <p class="text-sm"><span class="font-medium text-gray-600">Student ID:</span> <span class="text-gray-800">${data.student?.student_id || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-gray-600">College:</span> <span class="text-gray-800">${data.student?.college?.name || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-gray-600">Year Level:</span> <span class="text-gray-800">${data.student?.year_level || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-gray-600">Course:</span> <span class="text-gray-800">${data.student?.course || 'N/A'}</span></p>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-br from-gray-50 to-transparent rounded-xl p-4 border border-gray-100">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user-tie text-gray-600 text-sm"></i>
                                        </div>
                                        <h4 class="text-sm font-semibold text-gray-700">Counselor Information</h4>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-sm"><span class="font-medium text-gray-600">Name:</span> <span class="text-gray-800">${data.counselor?.name || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-gray-600">College:</span> <span class="text-gray-800">${data.counselor?.college?.name || 'N/A'}</span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Appointment Details Card -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-8 h-8 bg-[#F00000]/10 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-[#F00000] text-sm"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-700">Appointment Details</h4>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm"><span class="font-medium text-gray-600">Date:</span> <span class="text-gray-800">${data.formatted_date || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-gray-600">Time:</span> <span class="text-gray-800">${data.formatted_time || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-gray-600">Type:</span> <span class="text-gray-800">${data.appointment?.booking_type || 'N/A'}</span></p>
                                    </div>
                                    <div>
                                        <p class="text-sm"><span class="font-medium text-gray-600">Status:</span> 
                                            <span class="inline-flex ml-2 px-2 py-0.5 text-xs font-semibold rounded-full
                                                ${data.appointment?.status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                                data.appointment?.status === 'approved' ? 'bg-green-100 text-green-700' :
                                                data.appointment?.status === 'completed' ? 'bg-gray-100 text-[#820000]' :
                                                'bg-gray-100 text-gray-600'}">
                                                ${data.appointment?.status_display || 'N/A'}
                                            </span>
                                        </p>
                                        ${data.appointment?.case_number ? `<p class="text-sm mt-1"><span class="font-medium text-gray-600">Case #:</span> <span class="text-gray-800 font-mono">${data.appointment.case_number}</span></p>` : ''}
                                    </div>
                                </div>
                            </div>

                            <!-- Concern Section -->
                            ${data.appointment?.concern ? `
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-8 h-8 bg-[#F00000]/10 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-comment-dots text-[#F00000] text-sm"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-700">Student's Concern</h4>
                                </div>
                                <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">${data.appointment.concern}</p>
                            </div>
                            ` : ''}

                            <!-- Counselor Notes Section -->
                            ${data.appointment?.notes ? `
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-sticky-note text-gray-600 text-sm"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-700">Counselor's Notes</h4>
                                </div>
                                <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">${data.appointment.notes}</p>
                            </div>
                            ` : ''}

                            <!-- Referral Details -->
                            ${(data.appointment?.is_referred || data.appointment?.referral_reason || data.referral?.referred_to_name || data.referral?.referred_from_name) ? `
                            <div class="bg-[#FFF9E6] rounded-xl p-4 border border-[#FFE100]">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-8 h-8 bg-[#FFE100]/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-exchange-alt text-[#820000] text-sm"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-[#820000]">Referral Details</h4>
                                </div>
                                <div class="space-y-2 text-sm">
                                    ${data.referral?.referred_from_name ? `<p><span class="font-medium text-[#820000]">Referred from:</span> <span class="text-gray-700">${data.referral.referred_from_name}</span></p>` : ''}
                                    ${data.referral?.referred_to_name ? `<p><span class="font-medium text-[#820000]">Referred to:</span> <span class="text-gray-700">${data.referral.referred_to_name}</span></p>` : ''}
                                    ${data.formatted_referral_date ? `<p><span class="font-medium text-[#820000]">Referral date:</span> <span class="text-gray-700">${data.formatted_referral_date}</span></p>` : ''}
                                    ${data.appointment?.referral_reason ? `<p class="mt-2"><span class="font-medium text-[#820000]">Reason:</span><br><span class="text-gray-700">${data.appointment.referral_reason}</span></p>` : ''}
                                </div>
                            </div>
                            ` : ''}

                            ${sessionNotesHtml}

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap justify-end gap-3 pt-4 border-t border-gray-100">
                                ${data.student?.profile_url ? `
                                <a href="${data.student.profile_url}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-[#F00000] text-white rounded-xl hover:bg-[#D40000] transition text-sm font-medium">
                                    <i class="fas fa-user-graduate text-sm"></i>
                                    View Student Profile
                                </a>
                                ` : ''}
                                <button onclick="closeAppointmentModal()"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition text-sm font-medium">
                                    <i class="fas fa-times text-sm"></i>
                                    Close
                                </button>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error fetching appointment details:', error);
                    details.innerHTML = `
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                            </div>
                            <p class="text-red-500 font-medium">Error loading appointment details</p>
                            <p class="text-sm text-gray-400 mt-1">Please try again</p>
                            <button onclick="closeAppointmentModal()" class="mt-4 px-4 py-2 bg-gray-100 text-gray-600 rounded-lg">Close</button>
                        </div>
                    `;
                });
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAppointmentModal();
            }
        });
    </script>
@endsection