@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

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

    /* Animations */
    .fade-in { animation: fadeIn 0.5s ease-in; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

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

    /* Stats Cards */
    .stat-card {
        display: flex; align-items: center; gap: 1rem; padding: 1rem;
        border-radius: 0.75rem; border: 1px solid var(--border-soft);
        background: rgba(255,255,255,0.8);
    }
    .stat-icon-box {
        width: 3rem; height: 3rem; border-radius: 0.6rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; flex-shrink: 0;
    }
    .stat-icon-gray { background: rgba(229, 231, 235, 0.6); color: var(--maroon-700); }
    .stat-icon-gold { background: rgba(255, 249, 230, 0.6); color: var(--maroon-800); }
    .stat-icon-orange { background: rgba(255, 237, 213, 0.6); color: #c2410c; }
    .stat-icon-red { background: rgba(254, 242, 242, 0.6); color: var(--maroon-800); }
    
    .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); line-height: 1.2; }

    /* Form Elements */
    .input-field, .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.85rem; padding: 0.6rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }
    .field-label {
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary);
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em;
    }

    /* Buttons */
    .btn-action {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 0.6rem 1rem; border-radius: 0.6rem; font-weight: 600; font-size: 0.8rem;
        transition: all 0.2s ease; white-space: nowrap; gap: 0.5rem; text-decoration: none;
    }
    .btn-export {
        background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white;
        box-shadow: 0 4px 10px rgba(5, 150, 105, 0.15); border: none; cursor: pointer;
    }
    .btn-export:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(5, 150, 105, 0.2); }
    
    .btn-filter {
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: #fef9e7; border: none;
    }
    .btn-filter:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .btn-reset {
        background: white; color: var(--text-secondary); border: 1px solid var(--border-soft);
    }
    .btn-reset:hover { background: var(--bg-warm); color: var(--text-primary); border-color: var(--maroon-700); }

    /* Table Styling */
    .table-container { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 900px; }
    .custom-table thead th {
        background: rgba(250,248,245,0.8); color: var(--text-muted);
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
        padding: 0.75rem 1rem; border-bottom: 1px solid var(--border-soft);
        text-align: left;
    }
    .custom-table tbody td {
        padding: 0.85rem 1rem; border-bottom: 1px solid rgba(229, 224, 219, 0.5);
        color: var(--text-secondary); font-size: 0.8rem; vertical-align: middle;
    }
    .custom-table tbody tr:last-child td { border-bottom: none; }
    .custom-table tbody tr:hover { background: rgba(254,249,231,0.3); }

    /* Avatar & Info */
    .avatar-circle {
        width: 2.5rem; height: 2.5rem; border-radius: 50%;
        background: rgba(250,248,245,0.8); border: 1px solid var(--border-soft);
        display: flex; align-items: center; justify-content: center;
        color: var(--maroon-700); font-weight: 700; font-size: 0.75rem; flex-shrink: 0;
    }
    .student-name { font-weight: 600; color: var(--text-primary); font-size: 0.85rem; }
    .student-id { font-size: 0.7rem; color: var(--text-muted); font-family: monospace; }
    .student-meta { font-size: 0.65rem; color: var(--text-muted); margin-top: 0.1rem; }

    /* Badges */
    .session-badge {
        display: inline-block; padding: 0.25rem 0.6rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .badge-gold { background: rgba(255, 249, 230, 0.8); color: var(--maroon-800); border: 1px solid rgba(212, 175, 55, 0.3); }
    
    /* Dynamic Badge Colors (Used in JS too) */
    .badge-initial { background: rgba(229, 231, 235, 0.6); color: var(--maroon-800); border: 1px solid var(--border-soft); }
    .badge-follow_up { background: rgba(209, 250, 229, 0.8); color: #047857; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-crisis { background: rgba(254, 226, 226, 0.8); color: #b91c1c; border: 1px solid rgba(185, 28, 28, 0.3); }
    .badge-regular { background: rgba(255, 249, 230, 0.8); color: var(--maroon-800); border: 1px solid rgba(212, 175, 55, 0.3); }

    .badge-very_good { background: rgba(209, 250, 229, 0.8); color: #047857; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-good { background: rgba(229, 231, 235, 0.6); color: var(--maroon-800); border: 1px solid var(--border-soft); }
    .badge-neutral { background: rgba(254, 243, 199, 0.8); color: #92400e; border: 1px solid rgba(245, 158, 11, 0.3); }
    .badge-low { background: rgba(255, 237, 213, 0.8); color: #c2410c; border: 1px solid rgba(249, 115, 22, 0.3); }
    .badge-very_low { background: rgba(254, 226, 226, 0.8); color: #b91c1c; border: 1px solid rgba(185, 28, 28, 0.3); }

    /* Notes Preview */
    .notes-preview {
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden; color: var(--text-secondary); font-size: 0.8rem; line-height: 1.4;
    }

    /* Action Icons */
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 2rem; height: 2rem; border-radius: 0.5rem;
        transition: all 0.2s ease; background: transparent; border: none; cursor: pointer;
    }
    .action-view { color: var(--maroon-700); } .action-view:hover { background: rgba(122, 42, 42, 0.1); color: var(--maroon-900); }
    .action-edit { color: #059669; } .action-edit:hover { background: rgba(5, 150, 105, 0.1); color: #047857; }
    .action-list { color: var(--maroon-800); } .action-list:hover { background: rgba(92, 26, 26, 0.1); color: var(--maroon-900); }
    .action-add { color: var(--maroon-700); } .action-add:hover { background: rgba(122, 42, 42, 0.1); color: var(--maroon-900); }

    /* Alerts */
    .alert-toast {
        padding: 0.75rem 1rem; border-radius: 0.6rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 0.75rem;
        font-weight: 500; font-size: 0.85rem; margin-bottom: 1rem;
    }
    .alert-success { background: white; border-left: 4px solid #059669; color: #047857; }
    .alert-error { background: white; border-left: 4px solid #b91c1c; color: #b91c1c; }

    /* Modal */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center; z-index: 50;
    }
    .modal-content {
        background: white; border-radius: 0.75rem; width: 90%; max-width: 800px;
        max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .modal-header {
        padding: 1.25rem; border-bottom: 1px solid var(--border-soft);
        display: flex; justify-content: space-between; align-items: center;
    }
    .modal-body { padding: 1.5rem; }
    .modal-close { color: var(--text-muted); cursor: pointer; transition: color 0.2s; }
    .modal-close:hover { color: var(--maroon-700); }

    /* Mobile Adjustments */
    @media (max-width: 639px) {
        .header-actions { flex-direction: column; width: 100%; }
        .header-actions .btn-action { width: 100%; }
        .stat-card { padding: 0.75rem; }
        .stat-icon-box { width: 2.5rem; height: 2.5rem; font-size: 1rem; }
        .stat-value { font-size: 1.25rem; }
        .filter-grid { grid-template-columns: 1fr !important; }
        .filter-actions { flex-direction: column; width: 100%; }
        .filter-actions .btn-action { width: 100%; }
        .custom-table { font-size: 0.75rem; }
        .custom-table thead th, .custom-table tbody td { padding: 0.6rem 0.5rem; }
        .avatar-circle { width: 2rem; height: 2rem; font-size: 0.65rem; }
    }
</style>

<div class="min-h-screen notes-shell">
    <div class="notes-glow one"></div>
    <div class="notes-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
            <div class="page-header">
                <h1 class="text-xl sm:text-2xl font-bold">Session Notes Dashboard</h1>
                <p class="text-sm mt-1">View and manage all student session notes.</p>
            </div>
            <div class="header-actions flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <a href="{{ route('counselor.dashboard') }}"
                   class="btn-action btn-reset">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
                <button onclick="exportToExcel()"
                        class="btn-action btn-export">
                    <i class="fas fa-file-export"></i> Export to Excel
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-gray">
                    <i class="fas fa-notes-medical"></i>
                </div>
                <div>
                    <p class="stat-label">Total Notes</p>
                    <p class="stat-value">{{ $totalNotes }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-gold">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="stat-label">Students</p>
                    <p class="stat-value">{{ $totalStudents }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-gold" style="color: var(--gold-500);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <p class="stat-label">This Month</p>
                    <p class="stat-value">{{ $notesThisMonth }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-orange">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <p class="stat-label">Avg. Sessions</p>
                    <p class="stat-value">{{ number_format($averageSessionsPerStudent, 1) }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-red">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <p class="stat-label">Crisis Sessions</p>
                    <p class="stat-value">{{ $crisisSessions }}</p>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="panel-card mb-6 p-4 sm:p-5">
            <form method="GET" action="{{ route('counselor.session-notes.dashboard') }}">
                <div class="filter-grid grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="field-label">Search</label>
                        <div class="relative">
                            <input type="text" id="search" name="search"
                                   placeholder="‎ ‎ ‎ Search by student name, notes, or college..."
                                   value="{{ request('search') }}"
                                   class="input-field pl-10">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-[var(--text-muted)]"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Session Type Filter -->
                    <div>
                        <label for="session_type" class="field-label">Session Type</label>
                        <select id="session_type" name="session_type" class="select-field">
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
                        <label for="date_range" class="field-label">Date Range</label>
                        <select id="date_range" name="date_range" class="select-field">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="filter-actions flex flex-col sm:flex-row justify-between items-center mt-4 gap-3">
                    <div class="text-xs sm:text-sm text-[var(--text-secondary)]">
                        Showing {{ $sessionNotes->firstItem() ?? 0 }}-{{ $sessionNotes->lastItem() ?? 0 }} of {{ $sessionNotes->total() }} notes
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        <a href="{{ route('counselor.session-notes.dashboard') }}"
                           class="btn-action btn-reset w-full sm:w-auto">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                        <button type="submit"
                                class="btn-action btn-filter w-full sm:w-auto">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Session Notes Table -->
        <div class="panel-card overflow-hidden">
            @if($sessionNotes->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-notes-medical text-6xl text-[var(--border-soft)] mb-4"></i>
                    <p class="text-[var(--text-secondary)] text-lg font-medium">No session notes found.</p>
                    <p class="text-[var(--text-muted)] text-sm mt-1">Session notes will appear here after you create them.</p>
                    <a href="{{ route('counselor.appointments') }}"
                       class="btn-action btn-filter mt-4">
                        <i class="fas fa-calendar-plus"></i> Go to Appointments
                    </a>
                </div>
            @else
                <div class="table-container">
                    <table class="custom-table" id="sessionNotesTable">
                        <thead>
                            <tr>
                                <th class="w-[20%]">Student</th>
                                <th class="w-[15%]">College & Course</th>
                                <th class="w-[20%]">Session Info</th>
                                <th class="w-[25%]">Session Notes</th>
                                <th class="w-[10%]">Last Session</th>
                                <th class="w-[10%] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-soft)]/50">
                            @foreach($sessionNotes as $note)
                                <tr class="hover:bg-[rgba(254,249,231,0.3)] transition fade-in">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar-circle">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="student-name truncate">
                                                    {{ $note->student->user->first_name }} {{ $note->student->user->last_name }}
                                                </div>
                                                <div class="student-id">{{ $note->student->student_id }}</div>
                                                <div class="student-meta">Year {{ $note->student->year_level }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-medium text-[var(--text-primary)] text-xs">
                                            {{ $note->student->college->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-[var(--text-muted)] text-xs mt-0.5">
                                            {{ $note->student->program ?? 'Not specified' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-2">
                                            <!-- Session Number -->
                                            <span class="session-badge badge-gold">
                                                {{ $note->session_number }}{{ $note->session_number == 1 ? 'st' : ($note->session_number == 2 ? 'nd' : ($note->session_number == 3 ? 'rd' : 'th')) }} Session
                                            </span>

                                            <!-- Session Type -->
                                            <span class="session-badge {{ getSessionTypeColor($note->session_type) }}">
                                                {{ $note->session_type_label }}
                                            </span>

                                            <!-- Mood Level -->
                                            @if($note->mood_level)
                                                <span class="session-badge {{ getMoodLevelColor($note->mood_level) }}">
                                                    <i class="fas fa-smile mr-1"></i>{{ $note->mood_level_label }}
                                                </span>
                                            @endif

                                            <!-- Total Sessions -->
                                            <div class="text-[10px] text-[var(--text-muted)]">
                                                <i class="fas fa-chart-line mr-1"></i>
                                                Total: {{ $note->student_total_sessions }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="notes-preview">
                                            {{ Str::limit($note->notes, 100) }}
                                        </div>
                                        @if($note->follow_up_actions)
                                            <div class="mt-1 text-[10px] text-[var(--maroon-700)] font-medium">
                                                <i class="fas fa-tasks mr-1"></i>Has follow-up actions
                                            </div>
                                        @endif
                                        @if($note->requires_follow_up)
                                            <div class="mt-1 text-[10px] text-orange-600 font-medium">
                                                <i class="fas fa-calendar-check mr-1"></i>Follow-up scheduled
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-xs font-medium text-[var(--text-primary)]">
                                            {{ $note->session_date->format('M j, Y') }}
                                        </div>
                                        <div class="text-[10px] text-[var(--text-muted)]">
                                            {{ $note->session_date->diffForHumans() }}
                                        </div>
                                        @if($note->appointment)
                                            <div class="text-[10px] text-[var(--text-muted)]">
                                                {{ \Carbon\Carbon::parse($note->appointment->start_time)->format('g:i A') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button onclick="showSessionNoteDetails({{ $note->id }})"
                                               class="action-btn action-view" title="View Details">
                                                <i class="fas fa-eye text-xs"></i>
                                            </button>
                                            <a href="{{ route('counselor.session-notes.edit', $note) }}"
                                               class="action-btn action-edit" title="Edit Note">
                                                <i class="fas fa-pen-to-square text-xs"></i>
                                            </a>
                                            <a href="{{ route('counselor.session-notes.index', $note->student) }}"
                                               class="action-btn action-list" title="All Student Notes">
                                                <i class="fas fa-notes-medical text-xs"></i>
                                            </a>
                                            <a href="{{ route('counselor.session-notes.create', $note->student) }}"
                                               class="action-btn action-add" title="Add New Note">
                                                <i class="fas fa-plus text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t border-[var(--border-soft)] bg-[rgba(250,248,245,0.4)]">
                    {{ $sessionNotes->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Session Note Details Modal -->
<div id="sessionNoteModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold text-[var(--text-primary)]">Session Note Details</h3>
            <button onclick="closeSessionNoteModal()" class="modal-close">
                <i class="fas fa-xmark text-xl"></i>
            </button>
        </div>
        <div id="sessionNoteDetails" class="modal-body">
            <!-- Content will be loaded via AJAX -->
        </div>
    </div>
</div>

<script>
    // Export to Excel functionality
    function exportToExcel() {
        const exportBtn = event.target.closest('button');
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
        exportBtn.disabled = true;

        try {
            const table = document.getElementById('sessionNotesTable');
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Session Notes");

            const today = new Date().toISOString().split('T')[0];
            XLSX.writeFile(wb, `session_notes_${today}.xlsx`);
        } catch (error) {
            console.error('Error exporting to Excel:', error);
            alert('Error exporting session notes. Please try again.');
        } finally {
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        }
    }

    // Session Note Modal Functions
    function showSessionNoteDetails(noteId) {
        document.getElementById('sessionNoteDetails').innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-2xl text-[var(--maroon-700)] mb-4"></i>
                <p class="text-[var(--text-secondary)]">Loading session note details...</p>
            </div>
        `;
        document.getElementById('sessionNoteModal').classList.remove('hidden');

        fetch(`/counselor/session-notes/${noteId}/details`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                const modalContent = document.getElementById('sessionNoteDetails');
                modalContent.innerHTML = `
                    <div class="space-y-6">
                        <!-- Student Information -->
                        <div class="bg-[rgba(250,248,245,0.6)] rounded-lg p-4 border border-[var(--border-soft)]">
                            <h4 class="text-base font-bold text-[var(--maroon-800)] mb-3">Student Information</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase">Name</label>
                                    <p class="mt-1 text-sm font-medium text-[var(--text-primary)]">${data.student.user.first_name} ${data.student.user.last_name}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase">Student ID</label>
                                    <p class="mt-1 text-sm font-medium text-[var(--text-primary)]">${data.student.student_id}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase">College</label>
                                    <p class="mt-1 text-sm font-medium text-[var(--text-primary)]">${data.student.college?.name || 'N/A'}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase">Year Level</label>
                                    <p class="mt-1 text-sm font-medium text-[var(--text-primary)]">${data.student.year_level}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Session Information -->
                        <div class="bg-[rgba(255,249,230,0.4)] rounded-lg p-4 border border-[rgba(212,175,55,0.2)]">
                            <h4 class="text-base font-bold text-[var(--maroon-800)] mb-3">Session Information</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase">Session Date</label>
                                    <p class="mt-1 text-sm font-medium text-[var(--text-primary)]">${data.session_date_formatted}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase">Session Type</label>
                                    <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getSessionTypeColorClass(data.session_type)}">
                                        ${data.session_type_label}
                                    </span>
                                </div>
                                ${data.mood_level ? `
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase">Mood Level</label>
                                    <span class="mt-1 inline-flex px-2 py-1 text-xs rounded-full ${getMoodLevelColorClass(data.mood_level)}">
                                        <i class="fas fa-smile mr-1"></i>${data.mood_level_label}
                                    </span>
                                </div>
                                ` : ''}
                            </div>
                            ${data.appointment_time ? `
                            <div class="mt-3">
                                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase">Appointment Time</label>
                                <p class="mt-1 text-sm font-medium text-[var(--text-primary)]">${data.appointment_time}</p>
                            </div>
                            ` : ''}
                        </div>

                        <!-- Session Notes -->
                        <div>
                            <h4 class="text-base font-bold text-[var(--maroon-800)] mb-3">Session Notes</h4>
                            <div class="bg-white border border-[var(--border-soft)] rounded-lg p-4 shadow-sm">
                                <p class="text-sm text-[var(--text-secondary)] whitespace-pre-line leading-relaxed">${data.notes || 'No notes provided.'}</p>
                            </div>
                        </div>

                        <!-- Follow-up Actions -->
                        ${data.follow_up_actions ? `
                        <div>
                            <h4 class="text-base font-bold text-[var(--maroon-800)] mb-3">Follow-up Actions</h4>
                            <div class="bg-[rgba(254,243,199,0.3)] border border-[rgba(245,158,11,0.2)] rounded-lg p-4">
                                <p class="text-sm text-[var(--text-secondary)] whitespace-pre-line">${data.follow_up_actions}</p>
                            </div>
                        </div>
                        ` : ''}

                        <!-- Follow-up Information -->
                        ${data.requires_follow_up ? `
                        <div class="bg-[rgba(209,250,229,0.3)] rounded-lg p-4 border border-[rgba(16,185,129,0.2)]">
                            <h4 class="text-base font-bold text-[#047857] mb-3">Follow-up Information</h4>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check text-[#059669] mr-3 text-lg"></i>
                                <div>
                                    <p class="text-sm font-medium text-[#047857]">Follow-up Session Scheduled</p>
                                    ${data.next_session_date ? `
                                    <p class="text-sm text-[#047857]">Next session: ${data.next_session_date}</p>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        ` : ''}

                        <!-- Session Statistics -->
                        <div class="bg-[rgba(255,249,230,0.4)] rounded-lg p-4 border border-[rgba(212,175,55,0.2)]">
                            <h4 class="text-base font-bold text-[var(--maroon-800)] mb-3">Session Statistics</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase">Session Number</label>
                                    <p class="mt-1 text-sm font-medium text-[var(--text-primary)]">${data.session_number}${getOrdinalSuffix(data.session_number)} Session</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase">Total Sessions with Student</label>
                                    <p class="mt-1 text-sm font-medium text-[var(--text-primary)]">${data.total_sessions} sessions</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-[var(--border-soft)]">
                            <a href="/counselor/session-notes/${data.id}/edit"
                               class="btn-action btn-filter w-full sm:w-auto">
                                <i class="fas fa-pen-to-square"></i> Edit Note
                            </a>
                            <a href="/counselor/students/${data.student.id}/session-notes"
                               class="btn-action btn-reset w-full sm:w-auto">
                                <i class="fas fa-notes-medical"></i> All Student Notes
                            </a>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                console.error('Error fetching session note details:', error);
                document.getElementById('sessionNoteDetails').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-300 mb-4"></i>
                        <p class="text-red-500 font-medium">Error loading session note details. Please try again.</p>
                        <button onclick="closeSessionNoteModal()"
                                class="mt-4 btn-action btn-reset">
                                Close
                        </button>
                    </div>
                `;
            });
    }

    function closeSessionNoteModal() {
        document.getElementById('sessionNoteModal').classList.add('hidden');
    }

    // Helper functions for modal (Matching CSS classes)
    function getSessionTypeColorClass(sessionType) {
        const colors = {
            'initial': 'badge-initial',
            'follow_up': 'badge-follow_up',
            'crisis': 'badge-crisis',
            'regular': 'badge-regular'
        };
        return colors[sessionType] || 'badge-initial';
    }

    function getMoodLevelColorClass(moodLevel) {
        const colors = {
            'very_good': 'badge-very_good',
            'good': 'badge-good',
            'neutral': 'badge-neutral',
            'low': 'badge-low',
            'very_low': 'badge-very_low'
        };
        return colors[moodLevel] || 'badge-good';
    }

    function getOrdinalSuffix(number) {
        if (number % 100 >= 11 && number % 100 <= 13) return 'th';
        switch (number % 10) {
            case 1: return 'st';
            case 2: return 'nd';
            case 3: return 'rd';
            default: return 'th';
        }
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'sessionNoteModal') {
            closeSessionNoteModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSessionNoteModal();
        }
    });
</script>
@endsection

<?php
// Helper functions (Ensure these match the CSS classes defined above)
function getSessionTypeColor($sessionType) {
    $colors = [
        'initial' => 'badge-initial',
        'follow_up' => 'badge-follow_up',
        'crisis' => 'badge-crisis',
        'regular' => 'badge-regular'
    ];
    return $colors[$sessionType] ?? 'badge-initial';
}

function getMoodLevelColor($moodLevel) {
    $colors = [
        'very_good' => 'badge-very_good',
        'good' => 'badge-good',
        'neutral' => 'badge-neutral',
        'low' => 'badge-low',
        'very_low' => 'badge-very_low'
    ];
    return $colors[$moodLevel] ?? 'badge-good';
}
?>