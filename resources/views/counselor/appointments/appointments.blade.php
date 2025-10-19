@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
<body class="bg-gray-50">

    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Appointment Management</h1>
                <p class="text-gray-600 mt-1">Manage student appointments and session notes across all assigned colleges</p>
                @if(isset($allColleges) && $allColleges->count() > 1)
                <div class="flex items-center mt-2">
                    <span class="text-sm text-gray-500 mr-2">Assigned to:</span>
                    <div class="flex flex-wrap gap-1">
                        @foreach($allColleges as $college)
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs college-badge">
                                {{ $college->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('counselor.dashboard') }}"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Dashboard
                </a>
                <a href="{{ route('counselor.calendar') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-calendar-alt mr-2"></i>View Calendar
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Appointments</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $appointments->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $appointments->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Approved</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $appointments->where('status', 'approved')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-flag-checkered text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Completed</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $appointments->where('status', 'completed')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-exchange-alt text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Referred</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $appointments->where('status', 'referred')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-100 rounded-lg">
                        <i class="fas fa-clipboard-list text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">With Notes</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $appointments->where('has_session_notes', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('counselor.appointments') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Appointments</label>
                        <div class="relative">
                            <input type="text"
                                id="search"
                                name="search"
                                placeholder="Search by student name, ID, college, or concern..."
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div>
                        <label for="date_range" class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                        <select id="date_range" name="date_range"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">All Dates</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="upcoming" {{ request('date_range') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="past" {{ request('date_range') == 'past' ? 'selected' : '' }}>Past Appointments</option>
                        </select>
                    </div>

                    <!-- College Filter -->
                    <div>
                        <label for="college" class="block text-sm font-medium text-gray-700 mb-2">College</label>
                        <select id="college" name="college"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">All Colleges</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}" {{ request('college') == $college->id ? 'selected' : '' }}>
                                    {{ $college->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center mt-4">
                    <div class="text-sm text-gray-600">
                        Showing {{ $appointments->firstItem() ?? 0 }}-{{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }} appointments
                        @if(isset($allColleges) && $allColleges->count() > 1)
                            <span class="text-blue-600 ml-2">(Across {{ $allColleges->count() }} colleges)</span>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('counselor.appointments') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-refresh mr-2"></i>Reset
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-filter mr-2"></i>Apply Filters
                        </button>
                        <button type="button" onclick="exportAllAppointmentsToExcel()"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-file-export mr-2"></i>Export to Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Status Filter -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=all"
                class="px-4 py-2 rounded-lg {{ ($status === 'all' || !request('status')) ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    All Appointments
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=pending"
                class="px-4 py-2 rounded-lg {{ $status === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Pending
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=approved"
                class="px-4 py-2 rounded-lg {{ $status === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Approved
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=completed"
                class="px-4 py-2 rounded-lg {{ $status === 'completed' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Completed
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=referred"
                class="px-4 py-2 rounded-lg {{ $status === 'referred' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Referred
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=rejected"
                class="px-4 py-2 rounded-lg {{ $status === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Rejected
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=cancelled"
                class="px-4 py-2 rounded-lg {{ $status === 'cancelled' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Cancelled
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 fade-in">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 fade-in">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Appointments Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($appointments->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No appointments found.</p>
                    <p class="text-gray-400 text-sm mt-1">When students book appointments, they will appear here.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full" id="appointmentsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concern</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">College</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Notes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($appointments as $appointment)
                                @php
                                    // Define status colors with ALL possible statuses
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                        'completed' => 'bg-blue-100 text-blue-800',
                                        'referred' => 'bg-purple-100 text-purple-800'
                                    ];

                                    // Safe status color lookup with fallback
                                    $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800';

                                    // Use the new referral context method
                                    $statusText = $appointment->display_status;

                                    // Add special styling for referred appointments
                                    $rowClass = 'hover:bg-gray-50 transition fade-in';
                                    if ($appointment->is_referred_out) {
                                        $rowClass = 'hover:bg-purple-50 transition fade-in bg-purple-50';
                                    } elseif ($appointment->is_referred_in) {
                                        $rowClass = 'hover:bg-blue-50 transition fade-in bg-blue-50';
                                    }
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                                    @if($appointment->is_referred_out)
                                                        <span class="ml-2 text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                                                            <i class="fas fa-share"></i> Referred Out
                                                        </span>
                                                    @elseif($appointment->is_referred_in)
                                                        <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                            <i class="fas fa-reply"></i> Referred In
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $appointment->student->student_id }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    Year {{ $appointment->student->year_level }}
                                                    @if($appointment->is_referred_in)
                                                        • {{ $appointment->student->college->name ?? 'N/A' }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Date & Time Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                        </div>
                                    </td>

                                    <!-- Concern Column -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $appointment->concern }}">
                                            {{ Str::limit($appointment->concern, 50) }}
                                        </div>
                                    </td>

                                    <!-- College Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $appointment->student->college->name ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <!-- Status Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>

                                    <!-- Session Notes Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($appointment->has_session_notes)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-clipboard-check mr-1"></i>
                                                {{ $appointment->session_notes_count }} note(s)
                                            </span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 session-notes-badge">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Notes needed
                                            </span>
                                        @elseif($appointment->status === 'approved')
                                            <a href="{{ route('counselor.session-notes.create', ['student' => $appointment->student->id, 'appointment_id' => $appointment->id]) }}"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition">
                                                <i class="fas fa-plus mr-1"></i>
                                                Add notes
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>

                                    <!-- Actions Column -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="showAppointmentDetails({{ $appointment->id }})"
                                                    class="text-blue-600 hover:text-blue-900 transition"
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Session Notes Actions -->
                                            @if($appointment->status === 'completed' || $appointment->has_session_notes)
                                                <button onclick="showSessionNotes({{ $appointment->student->id }}, {{ $appointment->id }})"
                                                        class="text-purple-600 hover:text-purple-900 transition"
                                                        title="View Session Notes">
                                                    <i class="fas fa-clipboard-list"></i>
                                                </button>
                                            @endif

                                            <!-- Status Management Actions - Available for current counselor AND referred-to counselor -->
                                            @if($appointment->getEffectiveCounselorId() == $counselor->id)
                                                @if($appointment->status === 'pending')
                                                    <!-- Approve button -->
                                                    <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit"
                                                                class="text-green-600 hover:text-green-900 transition"
                                                                onclick="return confirm('Approve this appointment?')"
                                                                title="Approve Appointment">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <!-- Reject/Transfer buttons -->
                                                    <button onclick="showRejectionOptions({{ $appointment->id }})"
                                                            class="text-red-600 hover:text-red-900 transition"
                                                            title="Reject or Transfer Appointment">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @elseif($appointment->status === 'approved')
                                                    <!-- Complete and Cancel buttons -->
                                                    <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit"
                                                                class="text-blue-600 hover:text-blue-900 transition"
                                                                onclick="return confirm('Mark this appointment as completed?')"
                                                                title="Mark as Completed">
                                                            <i class="fas fa-flag-checkered"></i>
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit"
                                                                class="text-orange-600 hover:text-orange-900 transition"
                                                                onclick="return confirm('Cancel this appointment?')"
                                                                title="Cancel Appointment">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </form>
                                                @elseif($appointment->status === 'referred' && $appointment->referred_to_counselor_id == $counselor->id)
                                                    <!-- Special actions for referred appointments where this counselor is the receiver -->
                                                    <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit"
                                                                class="text-green-600 hover:text-green-900 transition"
                                                                onclick="return confirm('Accept this referred appointment?')"
                                                                title="Accept Referred Appointment">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <input type="hidden" name="notes" value="Unable to accept the referred appointment.">
                                                        <button type="submit"
                                                                class="text-red-600 hover:text-red-900 transition"
                                                                onclick="return confirm('Reject this referred appointment?')"
                                                                title="Reject Referred Appointment">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif

                                            <!-- Quick Student Profile Link -->
                                            <a href="{{ route('counselor.session-notes.index', $appointment->student) }}"
                                            class="text-indigo-600 hover:text-indigo-900 transition"
                                            title="View Student Profile & Notes">
                                                <i class="fas fa-user-circle"></i>
                                            </a>

                                            <!-- Transfer option for current counselor (except for referred-in appointments) -->
                                            @if($appointment->counselor_id == $counselor->id && $appointment->status !== 'referred')
                                                <button onclick="showTransferOptions({{ $appointment->id }})"
                                                        class="text-purple-600 hover:text-purple-900 transition"
                                                        title="Transfer to Another Counselor">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $appointments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Appointment Details Modal -->
        <div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800">Appointment Details</h3>
                        <button onclick="closeAppointmentModal()" class="text-gray-500 hover:text-gray-700 transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div id="appointmentDetails" class="p-6">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>

        <!-- Session Notes Modal -->
        <div id="sessionNotesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800">Session Notes</h3>
                        <button onclick="closeSessionNotesModal()" class="text-gray-500 hover:text-gray-700 transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div id="sessionNotesContent" class="p-6">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>

        <!-- Rejection Options Modal -->
        <div id="rejectionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800">Reject Appointment</h3>
                        <button onclick="closeRejectionModal()" class="text-gray-500 hover:text-gray-700 transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Choose how you want to handle this appointment:</p>

                    <div class="space-y-3">
                        <!-- Direct Rejection -->
                        <form id="directRejectForm" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <input type="hidden" name="notes" id="rejectionNotes" value="I am unavailable at this time. Please book with another counselor.">
                            <button type="submit"
                                    class="w-full text-left p-4 border border-red-300 rounded-lg hover:bg-red-50 transition">
                                <div class="flex items-center">
                                    <div class="p-2 bg-red-100 rounded-lg mr-3">
                                        <i class="fas fa-times text-red-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-red-800">Reject Directly</h4>
                                        <p class="text-sm text-red-600">Appointment will be cancelled</p>
                                    </div>
                                </div>
                            </button>
                        </form>

                        <!-- Transfer to Another Counselor -->
                        <button onclick="showTransferOptions({{ $appointment->id ?? 0 }})"
                                class="w-full text-left p-4 border border-blue-300 rounded-lg hover:bg-blue-50 transition">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                    <i class="fas fa-exchange-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-800">Transfer to Another Counselor</h4>
                                    <p class="text-sm text-blue-600">Appointment will be transferred to selected counselor</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transfer Options Modal -->
        <div id="transferModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800">Transfer Appointment</h3>
                        <button onclick="closeTransferModal()" class="text-gray-500 hover:text-gray-700 transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div id="transferModalContent">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Export to Excel functionality
            function exportAppointmentsToExcel() {
                // Show loading indicator
                const exportBtn = event.target;
                const originalText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
                exportBtn.disabled = true;

                try {
                    const table = document.getElementById('appointmentsTable');

                    // Create a copy of the table for export (without action buttons)
                    const exportTable = table.cloneNode(true);

                    // Remove action column from export
                    const headers = exportTable.getElementsByTagName('thead')[0].rows[0].cells;
                    const actionHeaderIndex = Array.from(headers).findIndex(th =>
                        th.textContent.trim() === 'Actions'
                    );

                    if (actionHeaderIndex > -1) {
                        // Remove action header
                        headers[actionHeaderIndex].remove();

                        // Remove action cells from all rows
                        const rows = exportTable.getElementsByTagName('tbody')[0].rows;
                        for (let row of rows) {
                            if (row.cells.length > actionHeaderIndex) {
                                row.deleteCell(actionHeaderIndex);
                            }
                        }
                    }

                    const ws = XLSX.utils.table_to_sheet(exportTable);
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "Appointments");

                    // Get current date for filename
                    const today = new Date().toISOString().split('T')[0];
                    const fileName = `appointments_${today}.xlsx`;

                    XLSX.writeFile(wb, fileName);

                } catch (error) {
                    console.error('Error exporting to Excel:', error);
                    alert('Error exporting appointments. Please try again.');
                } finally {
                    // Restore button state
                    exportBtn.innerHTML = originalText;
                    exportBtn.disabled = false;
                }
            }

            // Enhanced export with all data (including filtered results)
            function exportAllAppointmentsToExcel() {
                // Get current filters
                const search = document.getElementById('search').value;
                const dateRange = document.getElementById('date_range').value;
                const college = document.getElementById('college').value;
                const status = '{{ $status }}';

                // Show loading
                const exportBtn = event.target;
                const originalText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
                exportBtn.disabled = true;

                // Create export URL with current filters
                let exportUrl = '{{ route("counselor.appointments.export") }}?';
                const params = new URLSearchParams();

                if (search) params.append('search', search);
                if (dateRange) params.append('date_range', dateRange);
                if (college) params.append('college', college);
                if (status && status !== 'all') params.append('status', status);

                exportUrl += params.toString();

                // Trigger download
                window.location.href = exportUrl;

                // Restore button after a delay
                setTimeout(() => {
                    exportBtn.innerHTML = originalText;
                    exportBtn.disabled = false;
                }, 3000);
            }

            // Rejection Options Modal
            let currentAppointmentId = null;

            function showRejectionOptions(appointmentId) {
                currentAppointmentId = appointmentId;

                // Update form actions with the correct appointment ID
                const directRejectForm = document.getElementById('directRejectForm');
                directRejectForm.action = `/counselor/appointments/${appointmentId}/update-status`;

                document.getElementById('rejectionModal').classList.remove('hidden');
            }

            function closeRejectionModal() {
                document.getElementById('rejectionModal').classList.add('hidden');
                currentAppointmentId = null;
            }

            // Transfer Options Modal
            function showTransferOptions(appointmentId) {
                currentAppointmentId = appointmentId;
                closeRejectionModal();

                // Show loading state
                document.getElementById('transferModalContent').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-4"></i>
                        <p class="text-gray-600">Loading available counselors...</p>
                    </div>
                `;

                document.getElementById('transferModal').classList.remove('hidden');

                // Fetch available counselors
                fetch(`/counselor/appointments/${appointmentId}/available-counselors`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(counselors => {
                    console.log('Counselors data received:', counselors);

                    if (counselors.error) {
                        throw new Error(counselors.error);
                    }

                    let counselorOptions = '<option value="">Choose a counselor</option>';

                    if (!counselors || counselors.length === 0) {
                        counselorOptions = '<option value="">No other counselors available in the system</option>';
                        console.log('No counselors found in response');
                    } else {
                        // Sort counselors: same college first, then different colleges
                        counselors.sort((a, b) => {
                            if (a.same_college && !b.same_college) return -1;
                            if (!a.same_college && b.same_college) return 1;
                            return a.name.localeCompare(b.name);
                        });

                        // Group counselors by college type
                        const sameCollegeCounselors = counselors.filter(c => c.same_college);
                        const differentCollegeCounselors = counselors.filter(c => !c.same_college);

                        if (sameCollegeCounselors.length > 0) {
                            counselorOptions += '<optgroup label="Same College Counselors">';
                            sameCollegeCounselors.forEach(counselor => {
                                counselorOptions += `<option value="${counselor.id}">${counselor.name} - ${counselor.position}</option>`;
                            });
                            counselorOptions += '</optgroup>';
                        }

                        if (differentCollegeCounselors.length > 0) {
                            counselorOptions += '<optgroup label="Other College Counselors">';
                            differentCollegeCounselors.forEach(counselor => {
                                counselorOptions += `<option value="${counselor.id}">${counselor.name} - ${counselor.position} (${counselor.college})</option>`;
                            });
                            counselorOptions += '</optgroup>';
                        }

                        console.log(`Loaded ${counselors.length} counselors (${sameCollegeCounselors.length} same college, ${differentCollegeCounselors.length} different college)`);
                    }

                    const transferForm = `
                        <form id="transferForm" action="/counselor/appointments/${appointmentId}/transfer" method="POST">
                            @csrf
                            @method('PATCH')

                            <p class="text-gray-600 mb-4">Transfer this appointment to another counselor. You can choose counselors from any college.</p>

                            <!-- Statistics -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                    <div>
                                        <h4 class="font-semibold text-blue-800">Available Counselors</h4>
                                        <p class="text-blue-700 text-sm">
                                            Total: ${counselors ? counselors.length : 0} counselors available
                                            ${counselors && counselors.filter(c => c.same_college).length > 0 ?
                                                `(${counselors.filter(c => c.same_college).length} from same college,
                                                ${counselors.filter(c => !c.same_college).length} from other colleges)` :
                                                ''}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            ${!counselors || counselors.length === 0 ? `
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    <p class="text-red-800 text-sm">
                                        No other counselors available in the system.
                                        Please contact administration to add more counselors.
                                    </p>
                                </div>
                            </div>
                            ` : ''}

                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                                    <p class="text-yellow-800 text-sm">
                                        <strong>Note:</strong> You can now transfer to counselors from any college.
                                        The appointment will be transferred and status reset to "Pending" for the new counselor's approval.
                                    </p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="counselorSelect" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Counselor to Transfer To
                                </label>
                                <select name="new_counselor_id" id="counselorSelect"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        ${!counselors || counselors.length === 0 ? 'disabled' : 'required'}>
                                    ${counselorOptions}
                                </select>
                                <p class="text-gray-500 text-xs mt-1">
                                    Counselors are grouped by college for easier selection
                                </p>
                            </div>

                            <div class="mb-4">
                                <label for="transferReason" class="block text-sm font-medium text-gray-700 mb-2">
                                    Reason for Transfer
                                </label>
                                <textarea name="transfer_reason" id="transferReason" rows="3"
                                        placeholder="Please explain why you are transferring this appointment to another counselor..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        ${!counselors || counselors.length === 0 ? 'disabled' : 'required'}></textarea>
                                <p class="text-gray-500 text-xs mt-1">
                                    This reason will be visible to the student and the receiving counselor
                                </p>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button"
                                        onclick="closeTransferModal()"
                                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition ${!counselors || counselors.length === 0 ? 'opacity-50 cursor-not-allowed' : ''}"
                                        ${!counselors || counselors.length === 0 ? 'disabled' : ''}>
                                    <i class="fas fa-exchange-alt mr-2"></i>Transfer Appointment
                                </button>
                            </div>
                        </form>
                    `;

                    document.getElementById('transferModalContent').innerHTML = transferForm;
                })
                .catch(error => {
                    console.error('Error loading counselors:', error);
                    document.getElementById('transferModalContent').innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-2xl text-red-600 mb-4"></i>
                            <p class="text-red-500 mb-2">Error loading available counselors.</p>
                            <p class="text-gray-600 text-sm mb-4">Error: ${error.message}</p>
                            <div class="space-x-2">
                                <button onclick="closeTransferModal()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                                    Close
                                </button>
                                <button onclick="showTransferOptions(${appointmentId})" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Try Again
                                </button>
                            </div>
                        </div>
                    `;
                });
            }

            function closeTransferModal() {
                document.getElementById('transferModal').classList.add('hidden');
                currentAppointmentId = null;
            }

            // Appointment Details Modal
            function showAppointmentDetails(appointmentId) {
                fetch(`/counselor/appointments/${appointmentId}/details`)
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
                                        <p class="mt-1 text-sm text-gray-900">${data.student.user.first_name} ${data.student.user.last_name}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Student ID</label>
                                        <p class="mt-1 text-sm text-gray-900">${data.student.student_id}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">College</label>
                                        <p class="mt-1 text-sm text-gray-900">${data.student.college?.name || 'N/A'}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Year Level</label>
                                        <p class="mt-1 text-sm text-gray-900">${data.student.year_level}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date</label>
                                        <p class="mt-1 text-sm text-gray-900">${data.formatted_date}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Time</label>
                                        <p class="mt-1 text-sm text-gray-900">${data.formatted_time}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Concern</label>
                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">${data.appointment.concern}</p>
                                </div>

                                ${data.appointment.notes ? `
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Counselor Notes</label>
                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">${data.appointment.notes}</p>
                                </div>
                                ` : ''}

    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full
            ${data.appointment.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
            data.appointment.status === 'approved' ? 'bg-green-100 text-green-800' :
            data.appointment.status === 'rejected' ? 'bg-red-100 text-red-800' :
            data.appointment.status === 'referred' ? 'bg-purple-100 text-purple-800' :
            'bg-gray-100 text-gray-800'}">
            ${data.appointment.status_display || (data.appointment.status.charAt(0).toUpperCase() + data.appointment.status.slice(1))}
        </span>
    </div>

                                ${data.appointment.status === 'transferred' && data.appointment.counselor ? `
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-exchange-alt text-purple-600 mr-2"></i>
                                        <div>
                                            <h4 class="font-semibold text-purple-800">Transferred To</h4>
                                            <p class="text-purple-700 text-sm">
                                                ${data.appointment.counselor.user.first_name} ${data.appointment.counselor.user.last_name}
                                                ${data.appointment.counselor.college ? `(${data.appointment.counselor.college.name})` : ''}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}

                                ${data.appointment.has_session_notes ? `
                                <div class="border-t pt-4 mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Session Notes</h4>
                                    <div class="space-y-3">
                                        ${data.session_notes.map(note => `
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

                                ${data.appointment.status === 'completed' && !data.appointment.has_session_notes ? `
                                <div class="border-t pt-4 mt-4">
                                    <a href="/counselor/students/${data.student.id}/session-notes/create?appointment_id=${data.appointment.id}"
                                    class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-plus mr-1"></i> Add Session Notes
                                    </a>
                                </div>
                                ` : ''}
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

            // Session Notes Modal
            function showSessionNotes(studentId, appointmentId = null) {
                let url = `/counselor/students/${studentId}/session-notes/json`;
                if (appointmentId) {
                    url += `?appointment_id=${appointmentId}`;
                }

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(notes => {
                        console.log('Received notes:', notes);
                        const modal = document.getElementById('sessionNotesModal');
                        const content = document.getElementById('sessionNotesContent');

                        if (notes.length === 0) {
                            content.innerHTML = `
                                <div class="text-center py-8">
                                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No session notes found.</p>
                                    <a href="/counselor/students/${studentId}/session-notes/create${appointmentId ? `?appointment_id=${appointmentId}` : ''}"
                                    class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                        Create First Session Note
                                    </a>
                                </div>
                            `;
                        } else {
                            content.innerHTML = `
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-lg font-semibold text-gray-800">Session History</h4>
                                    <a href="/counselor/students/${studentId}/session-notes/create${appointmentId ? `?appointment_id=${appointmentId}` : ''}"
                                    class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition text-sm">
                                        <i class="fas fa-plus mr-1"></i> Add Note
                                    </a>
                                </div>
                                <div class="space-y-4">
                                    ${notes.map(note => `
                                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 ${getSessionNoteBorderColor(note.session_type)}">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <h5 class="font-semibold text-gray-800">${note.session_type_label || note.session_type}</h5>
                                                    <p class="text-sm text-gray-600">${note.session_date || 'No date'} • Created: ${new Date(note.created_at).toLocaleDateString()}</p>
                                                </div>
                                                ${note.mood_level ? `
                                                <span class="text-xs px-2 py-1 rounded-full ${getMoodLevelColor(note.mood_level)}">
                                                    ${note.mood_level_label || note.mood_level}
                                                </span>
                                                ` : ''}
                                            </div>
                                            <div class="mb-3">
                                                <p class="text-sm text-gray-700 whitespace-pre-line">${note.notes || 'No notes provided'}</p>
                                            </div>
                                            ${note.follow_up_actions ? `
                                            <div class="bg-white rounded p-3 mb-3">
                                                <h6 class="text-xs font-medium text-gray-600 mb-1">Follow-up Actions:</h6>
                                                <p class="text-sm text-gray-700 whitespace-pre-line">${note.follow_up_actions}</p>
                                            </div>
                                            ` : ''}
                                            ${note.requires_follow_up && note.next_session_date ? `
                                            <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                                                <div class="flex items-center">
                                                    <i class="fas fa-calendar-check text-yellow-600 mr-2"></i>
                                                    <span class="text-sm text-yellow-800">
                                                        Next session: ${note.next_session_date}
                                                    </span>
                                                </div>
                                            </div>
                                            ` : ''}
                                        </div>
                                    `).join('')}
                                </div>
                            `;
                        }

                        modal.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching session notes:', error);
                        const modal = document.getElementById('sessionNotesModal');
                        const content = document.getElementById('sessionNotesContent');
                        content.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-4xl text-red-300 mb-4"></i>
                                <p class="text-red-500">Error loading session notes. Please try again.</p>
                            </div>
                        `;
                        modal.classList.remove('hidden');
                    });
            }

            function closeSessionNotesModal() {
                document.getElementById('sessionNotesModal').classList.add('hidden');
            }

            // Helper functions for styling
            function getSessionNoteBorderColor(sessionType) {
                const colors = {
                    'initial': 'border-blue-500',
                    'follow_up': 'border-green-500',
                    'crisis': 'border-red-500',
                    'regular': 'border-purple-500'
                };
                return colors[sessionType] || 'border-gray-500';
            }

            function getMoodLevelColor(moodLevel) {
                const colors = {
                    'very_good': 'bg-green-100 text-green-800',
                    'good': 'bg-blue-100 text-blue-800',
                    'neutral': 'bg-yellow-100 text-yellow-800',
                    'low': 'bg-orange-100 text-orange-800',
                    'very_low': 'bg-red-100 text-red-800'
                };
                return colors[moodLevel] || 'bg-gray-100 text-gray-800';
            }

            // Close modals when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target.id === 'appointmentModal') {
                    closeAppointmentModal();
                }
                if (e.target.id === 'sessionNotesModal') {
                    closeSessionNotesModal();
                }
                if (e.target.id === 'rejectionModal') {
                    closeRejectionModal();
                }
                if (e.target.id === 'transferModal') {
                    closeTransferModal();
                }
            });
        </script>
    @endsection
