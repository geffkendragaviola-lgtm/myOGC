@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .notes-preview {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .session-badge {
            font-size: 0.7rem;
            padding: 2px 6px;
        }
    </style>
</head>
<body class="bg-gray-50">


    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Session Notes Dashboard</h1>
                <p class="text-gray-600 mt-1">View and manage all student session notes</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('counselor.dashboard') }}"
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Dashboard
                </a>
                <button onclick="exportToExcel()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-export mr-2"></i>Export to Excel
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Notes</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalNotes }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Students</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalStudents }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">This Month</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $notesThisMonth }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i class="fas fa-star text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Avg. Sessions</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($averageSessionsPerStudent, 1) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Crisis Sessions</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $crisisSessions }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('counselor.session-notes.dashboard') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative">
                            <input type="text"
                                   id="search"
                                   name="search"
                                   placeholder="Search by student name, notes, or college..."
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Session Type Filter -->
                    <div>
                        <label for="session_type" class="block text-sm font-medium text-gray-700 mb-2">Session Type</label>
                        <select id="session_type" name="session_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">All Types</option>
                            @foreach($sessionTypes as $value => $label)
                                <option value="{{ $value }}" {{ request('session_type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label for="date_range" class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                        <select id="date_range" name="date_range"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center mt-4">
                    <div class="text-sm text-gray-600">
                        Showing {{ $sessionNotes->firstItem() ?? 0 }}-{{ $sessionNotes->lastItem() ?? 0 }} of {{ $sessionNotes->total() }} session notes
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('counselor.session-notes.dashboard') }}"
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-refresh mr-2"></i>Reset
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-filter mr-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </form>
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

        <!-- Session Notes Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($sessionNotes->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No session notes found.</p>
                    <p class="text-gray-400 text-sm mt-1">Session notes will appear here after you create them.</p>
                    <a href="{{ route('counselor.appointments') }}"
                       class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-calendar-plus mr-2"></i>Go to Appointments
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full" id="sessionNotesTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">College & Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Info</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Notes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Session</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sessionNotes as $note)
                                <tr class="hover:bg-gray-50 transition fade-in">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $note->student->user->first_name }} {{ $note->student->user->last_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $note->student->student_id }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    Year {{ $note->student->year_level }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $note->student->college->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $note->student->program ?? 'Not specified' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col space-y-2">
                                            <!-- Session Number -->
                                            <div class="flex items-center">
                                                <span class="session-badge px-2 py-1 rounded-full bg-indigo-100 text-indigo-800 font-semibold">
                                                    {{ $note->session_number }}{{ $note->session_number == 1 ? 'st' : ($note->session_number == 2 ? 'nd' : ($note->session_number == 3 ? 'rd' : 'th')) }} Session
                                                </span>
                                            </div>

                                            <!-- Session Type -->
                                            <div class="flex items-center">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ getSessionTypeColor($note->session_type) }}">
                                                    {{ $note->session_type_label }}
                                                </span>
                                            </div>

                                            <!-- Mood Level -->
                                            @if($note->mood_level)
                                                <div class="flex items-center">
                                                    <span class="px-2 py-1 text-xs rounded-full {{ getMoodLevelColor($note->mood_level) }}">
                                                        <i class="fas fa-smile mr-1"></i>{{ $note->mood_level_label }}
                                                    </span>
                                                </div>
                                            @endif

                                            <!-- Total Sessions for Student -->
                                            <div class="text-xs text-gray-500">
                                                <i class="fas fa-chart-line mr-1"></i>
                                                Total: {{ $note->student_total_sessions }} sessions
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="notes-preview text-sm text-gray-600 mb-2">
                                            {{ Str::limit($note->notes, 100) }}
                                        </div>
                                        @if($note->follow_up_actions)
                                            <div class="mt-1 text-xs text-blue-600">
                                                <i class="fas fa-tasks mr-1"></i>Has follow-up actions
                                            </div>
                                        @endif
                                        @if($note->requires_follow_up)
                                            <div class="mt-1 text-xs text-orange-600">
                                                <i class="fas fa-calendar-check mr-1"></i>Follow-up scheduled
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $note->session_date->format('M j, Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $note->session_date->diffForHumans() }}
                                        </div>
                                        @if($note->appointment)
                                            <div class="text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($note->appointment->start_time)->format('g:i A') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('counselor.session-notes.show', $note) }}"
                                               class="text-blue-600 hover:text-blue-900 transition"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('counselor.session-notes.edit', $note) }}"
                                               class="text-green-600 hover:text-green-900 transition"
                                               title="Edit Note">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('counselor.session-notes.index', $note->student) }}"
                                               class="text-purple-600 hover:text-purple-900 transition"
                                               title="All Student Notes">
                                                <i class="fas fa-clipboard-list"></i>
                                            </a>
                                            <a href="{{ route('counselor.session-notes.create', $note->student) }}"
                                               class="text-indigo-600 hover:text-indigo-900 transition"
                                               title="Add New Note">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $sessionNotes->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Export to Excel functionality
        function exportToExcel() {
            const table = document.getElementById('sessionNotesTable');
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Session Notes");

            // Get current date for filename
            const today = new Date().toISOString().split('T')[0];
            XLSX.writeFile(wb, `session_notes_${today}.xlsx`);
        }
 // Enhanced Export to Excel functionality
        function exportToExcel() {
            // Show loading indicator
            const exportBtn = event.target;
            const originalText = exportBtn.innerHTML;
            exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
            exportBtn.disabled = true;

            try {
                const table = document.getElementById('sessionNotesTable');

                // Create a copy of the table for export
                const exportTable = table.cloneNode(true);

                // Remove action buttons and other non-essential columns if needed
                const headers = exportTable.getElementsByTagName('thead')[0].rows[0].cells;

                // Create worksheet from table
                const ws = XLSX.utils.table_to_sheet(exportTable);

                // Auto-size columns
                const colWidths = [];
                const range = XLSX.utils.decode_range(ws['!ref']);
                for (let C = range.s.c; C <= range.e.c; ++C) {
                    let max_width = 0;
                    for (let R = range.s.r; R <= range.e.r; ++R) {
                        const cell = ws[XLSX.utils.encode_cell({c: C, r: R})];
                        if (cell && cell.v) {
                            const cellWidth = cell.v.toString().length;
                            if (cellWidth > max_width) max_width = cellWidth;
                        }
                    }
                    colWidths.push({wch: Math.min(max_width + 2, 50)}); // Max width 50 characters
                }
                ws['!cols'] = colWidths;

                // Create workbook and append worksheet
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "Session Notes");

                // Get current date for filename
                const today = new Date().toISOString().split('T')[0];
                const fileName = `session_notes_${today}.xlsx`;

                // Export to Excel
                XLSX.writeFile(wb, fileName);

            } catch (error) {
                console.error('Error exporting to Excel:', error);
                alert('Error exporting session notes. Please try again.');
            } finally {
                // Restore button state
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
            }
        }

        // Alternative export function with better formatting
        function exportToExcelEnhanced() {
            try {
                // Get all session note data
                const table = document.getElementById('sessionNotesTable');
                const data = [];

                // Get headers
                const headers = [];
                const headerRow = table.rows[0];
                for (let i = 0; i < headerRow.cells.length; i++) {
                    headers.push(headerRow.cells[i].textContent.trim());
                }
                data.push(headers);

                // Get data rows
                for (let i = 1; i < table.rows.length; i++) {
                    const row = table.rows[i];
                    const rowData = [];
                    for (let j = 0; j < row.cells.length; j++) {
                        rowData.push(row.cells[j].textContent.trim());
                    }
                    data.push(rowData);
                }

                // Create worksheet
                const ws = XLSX.utils.aoa_to_sheet(data);

                // Set column widths
                const colWidths = headers.map((header, index) => {
                    const maxLength = Math.max(...data.map(row => (row[index] || '').toString().length));
                    return { wch: Math.min(maxLength + 2, 50) };
                });
                ws['!cols'] = colWidths;

                // Create workbook
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "Session Notes");

                // Export
                const today = new Date().toISOString().split('T')[0];
                XLSX.writeFile(wb, `session_notes_${today}.xlsx`);

            } catch (error) {
                console.error('Error exporting:', error);
                alert('Error exporting session notes. Please try again.');
            }
        }

    </script>
@endsection

<?php
// Helper functions (add these to your controller or a helper file)
function getSessionTypeColor($sessionType) {
    $colors = [
        'initial' => 'bg-blue-100 text-blue-800',
        'follow_up' => 'bg-green-100 text-green-800',
        'crisis' => 'bg-red-100 text-red-800',
        'regular' => 'bg-purple-100 text-purple-800'
    ];
    return $colors[$sessionType] ?? 'bg-gray-100 text-gray-800';
}

function getMoodLevelColor($moodLevel) {
    $colors = [
        'very_good' => 'bg-green-100 text-green-800',
        'good' => 'bg-blue-100 text-blue-800',
        'neutral' => 'bg-yellow-100 text-yellow-800',
        'low' => 'bg-orange-100 text-orange-800',
        'very_low' => 'bg-red-100 text-red-800'
    ];
    return $colors[$moodLevel] ?? 'bg-gray-100 text-gray-800';
}
?>

