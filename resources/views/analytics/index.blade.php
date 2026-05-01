@extends('layouts.admin')

@section('title', 'Analytics — OGC')

@push('styles')
<style>
/* ── Analytics page styles ── */
.analytics-card {
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: 1rem;
    box-shadow: 0 4px 18px rgba(44,36,32,0.07);
    padding: 1.5rem;
}
.stat-card {
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: 1rem;
    box-shadow: 0 4px 18px rgba(44,36,32,0.07);
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.stat-icon {
    width: 3rem; height: 3rem;
    border-radius: 0.75rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.stat-icon.maroon { background: #7a2a2a; color:#fff; }
.stat-icon.gold   { background: #c9a227; color:#fff; }
.stat-icon.green  { background: #2d7a4f; color:#fff; }
.stat-icon.blue   { background: #2a5a7a; color:#fff; }
.stat-value { font-size: 1.75rem; font-weight: 600; color: var(--text-primary); line-height:1; }
.stat-label { font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.2rem; font-weight: 500; }
.section-title {
    font-size: 1rem; font-weight: 700; color: var(--text-primary);
    margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;
}
.section-title i { color: var(--maroon-soft); }
.filter-bar {
    position: relative;
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: 1rem;
    padding: 1rem 1.25rem;
    box-shadow: 0 2px 10px rgba(44,36,32,0.05);
    overflow: hidden;
}
.filter-bar::before {
    content: "";
    position: absolute;
    inset-inline: 0;
    top: 0;
    height: 3px;
    background: linear-gradient(90deg, #5c1a1a 0%, #d4af37 50%, #5c1a1a 100%);
}
.filter-bar select, .filter-bar input[type=date] {
    width: 100%;
    border: 1px solid var(--border-soft);
    border-radius: 0.5rem;
    padding: 0.45rem 0.75rem;
    font-size: 0.875rem;
    color: var(--text-primary);
    background: var(--bg-warm);
    outline: none;
    transition: border-color 0.2s;
}
.filter-bar select:focus, .filter-bar input[type=date]:focus {
    border-color: var(--maroon-soft);
}
.btn-maroon {
    background: linear-gradient(135deg,var(--maroon-soft),var(--maroon-medium));
    color: #fff; border: none; border-radius: 0.5rem;
    padding: 0.5rem 1.1rem; font-size: 0.875rem; font-weight: 600;
    cursor: pointer; transition: opacity 0.2s;
}
.btn-maroon:hover { opacity: 0.88; }
.btn-outline {
    background: transparent;
    color: var(--maroon-soft);
    border: 1.5px solid var(--maroon-soft);
    border-radius: 0.5rem;
    padding: 0.45rem 1rem; font-size: 0.875rem; font-weight: 600;
    cursor: pointer; transition: all 0.2s; text-decoration: none;
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.btn-outline:hover { background: var(--maroon-soft); color: #fff; }
.status-badge {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.3rem 0.7rem; border-radius: 999px;
    font-size: 0.75rem; font-weight: 600;
}
.chart-wrap { position: relative; }
.empty-state {
    text-align: center; padding: 2.5rem 1rem;
    color: var(--text-muted); font-size: 0.9rem;
}
.empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.35; display: block; }
.progress-bar-wrap { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.6rem; }
.progress-bar-label { font-size: 0.8rem; color: var(--text-secondary); min-width: 7rem; }
.progress-bar-track { flex: 1; height: 0.55rem; background: var(--border-soft); border-radius: 999px; overflow: hidden; }
.progress-bar-fill { height: 100%; border-radius: 999px; transition: width 0.6s ease; }
.progress-bar-count { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); min-width: 2.5rem; text-align: right; }

/* ── Print styles ── */
@media print {
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; box-sizing: border-box; }

    @page { size: A4 portrait; margin: 1.8cm 1.5cm 2.2cm 1.5cm; }
    @page { @bottom-left { content: "MSU-IIT Office of Guidance and Counseling — Confidential"; font-size: 7.5pt; color: #8b7e76; font-family: 'Segoe UI', sans-serif; } @bottom-right { content: "Page " counter(page) " of " counter(pages); font-size: 7.5pt; color: #8b7e76; font-family: 'Segoe UI', sans-serif; } }

    body { background: #fff !important; font-size: 9.5pt; color: #1a1a1a; font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.4; }

    /* Hide screen-only elements */
    .no-print, .ogc-navbar, #ogcSidebar, .filter-bar, button, nav, aside { display: none !important; }
    #ogcMainContent { margin-left: 0 !important; padding-top: 0 !important; }
    .p-6 { padding: 0 !important; }
    .space-y-6 > * + * { margin-top: 0.6rem !important; }

    /* ── Report header ── */
    .print-header {
        display: block !important;
        margin-bottom: 1.2rem;
        padding-bottom: 0.8rem;
        border-bottom: 2.5pt solid #7a2a2a;
    }
    .print-header-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }
    .print-header h1 { font-size: 15pt; font-weight: 800; color: #3a0c0c; margin: 0; letter-spacing: -0.02em; }
    .print-header .subtitle { font-size: 9pt; color: #7a2a2a; font-weight: 600; margin: 0.15rem 0 0; text-transform: uppercase; letter-spacing: 0.06em; }
    .print-header .meta-block { text-align: right; font-size: 8pt; color: #6b5e57; line-height: 1.6; }
    .print-header .meta-row { display: flex; gap: 1.5rem; flex-wrap: wrap; font-size: 8pt; color: #6b5e57; margin-top: 0.4rem; padding-top: 0.4rem; border-top: 0.5pt solid #d4c4bc; }
    .print-header .meta-row span { display: inline-flex; gap: 0.3rem; }
    .print-header .meta-row strong { color: #3a0c0c; }

    /* ── Section headings ── */
    .print-only { display: block !important; }
    .section-title, .print-section-title {
        font-size: 9pt !important;
        font-weight: 700;
        color: #3a0c0c;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        border-bottom: 1pt solid #c9a227;
        padding-bottom: 0.25rem;
        margin-bottom: 0.6rem !important;
        display: block !important;
    }
    .section-title i { display: none; }

    /* ── Cards ── */
    .analytics-card, .stat-card {
        box-shadow: none !important;
        border: 0.5pt solid #d4c4bc !important;
        border-radius: 0 !important;
        break-inside: avoid;
        page-break-inside: avoid;
        margin-bottom: 0.6rem;
        background: #fff !important;
    }
    .analytics-card { padding: 0.7rem 0.8rem !important; }
    .stat-card { padding: 0.5rem 0.7rem !important; gap: 0.5rem !important; }
    .stat-value { font-size: 13pt !important; font-weight: 700; line-height: 1; }
    .stat-label { font-size: 7pt !important; color: #6b5e57; margin-top: 0.1rem; }
    .stat-icon { width: 1.8rem !important; height: 1.8rem !important; font-size: 0.75rem !important; border-radius: 0.3rem !important; }

    /* ── Summary grid ── */
    .grid { display: grid !important; }
    .grid-cols-2, .sm\:grid-cols-3, .lg\:grid-cols-4 { grid-template-columns: repeat(4, 1fr) !important; gap: 0.4rem !important; }
    .lg\:grid-cols-5, .sm\:grid-cols-3 { grid-template-columns: repeat(5, 1fr) !important; gap: 0.4rem !important; }
    .lg\:grid-cols-2 { grid-template-columns: repeat(2, 1fr) !important; gap: 0.5rem !important; }
    .md\:grid-cols-2 { grid-template-columns: repeat(2, 1fr) !important; gap: 0.5rem !important; }

    /* ── Charts ── */
    canvas { max-width: 100% !important; height: auto !important; page-break-inside: avoid; }
    .chart-wrap { height: 180px !important; page-break-inside: avoid; }

    /* ── Progress bars ── */
    .progress-bar-wrap { margin-bottom: 0.3rem !important; gap: 0.4rem !important; }
    .progress-bar-label { font-size: 7.5pt !important; min-width: 8rem !important; }
    .progress-bar-count { font-size: 7.5pt !important; font-weight: 700; }
    .progress-bar-track { height: 0.4rem !important; }

    /* ── Referral overview ── */
    .print-referral-table { width: 100%; border-collapse: collapse; font-size: 8pt; margin-top: 0.4rem; }
    .print-referral-table th { background: #f5f0eb !important; color: #3a0c0c; font-weight: 700; padding: 0.3rem 0.5rem; text-align: left; border: 0.5pt solid #d4c4bc; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.04em; }
    .print-referral-table td { padding: 0.3rem 0.5rem; border: 0.5pt solid #e5e0db; vertical-align: middle; }
    .print-referral-table tr:nth-child(even) td { background: #faf8f5 !important; }
    .print-referral-table .val { font-weight: 700; color: #3a0c0c; text-align: right; }
    .print-referral-table .pct { color: #6b5e57; text-align: right; }

    /* ── Signature block ── */
    .print-signature {
        display: block !important;
        margin-top: 2rem !important;
        padding-top: 1rem;
        border-top: 0.5pt solid #d4c4bc;
        page-break-inside: avoid;
    }
    .print-sig-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem; }
    .print-sig-item { font-size: 8pt; color: #6b5e57; }
    .print-sig-line { border-top: 0.75pt solid #3a0c0c; margin-top: 1.8rem; padding-top: 0.3rem; }
    .print-sig-name { font-weight: 700; color: #1a1a1a; font-size: 8.5pt; }
    .print-sig-role { color: #6b5e57; font-size: 7.5pt; }

    /* ── Misc ── */
    a { color: inherit !important; text-decoration: none !important; }
    .space-y-6 { gap: 0 !important; }
    .gap-4, .gap-6 { gap: 0.4rem !important; }
    .mb-6, .mb-5 { margin-bottom: 0.6rem !important; }
}

.print-header { display: none; }
.print-only { display: none; }
.print-signature { display: none; }
.print-referral-table { display: none; }
</style>
@endpush

@section('content')
<style>
.admin-shell { position: relative; overflow: hidden; background: #faf8f5; min-height: 100vh; }
.admin-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25; }
.admin-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: #d4af37; }
.admin-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: #5c1a1a; }
.hero-card {
    position: relative; overflow: hidden; border-radius: 0.75rem;
    border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
    backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
}
.hero-card::before {
    content: ""; position: absolute; inset: 0; pointer-events: none;
    background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
}
.hero-icon {
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
    background: linear-gradient(135deg, #5c1a1a 0%, #7a2a2a 100%);
    box-shadow: 0 4px 12px rgba(92,26,26,0.15);
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
    border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.8);
    padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.16em; color: #7a2a2a;
}
.hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: #d4af37; }

.summary-card {
    position: relative; overflow: hidden; border-radius: 0.75rem;
    border: 1px solid rgba(92,26,26,0.15);
    background: linear-gradient(135deg, #5c1a1a 0%, #3a0c0c 100%); color: white;
    box-shadow: 0 4px 12px rgba(58,12,12,0.15);
}
.summary-card::before {
    content: ""; position: absolute; inset: 0; opacity: 0.15;
    background: radial-gradient(circle at top right, #d4af37, transparent 40%);
    pointer-events: none;
}
.summary-icon {
    width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
    align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
}
.summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
.summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
.summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

.primary-btn {
    border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
    display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
    color: #fef9e7; background: linear-gradient(135deg, #5c1a1a 0%, #7a2a2a 100%);
    box-shadow: 0 4px 10px rgba(92,26,26,0.15);
}
.primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
</style>

<div class="min-h-screen admin-shell">
    <div class="admin-glow one"></div>
    <div class="admin-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8 space-y-6">

        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card no-print">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon"><i class="fas fa-chart-column text-base sm:text-lg"></i></div>
                        <div class="min-w-0">
                            <div class="hero-badge"><span class="hero-badge-dot"></span>Admin Panel</div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Analytics</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">Appointment and counseling insights</p>
                        </div>
                    </div>
                </div>

                <div class="summary-card no-print">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-print text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Quick Action</p>
                                <p class="summary-value">Print Report</p>
                                <p class="summary-subtext hidden sm:block">Generate a printable version of this analytics data.</p>
                            </div>
                        </div>
                        <button onclick="exportWord()" class="primary-btn px-5 py-2.5 whitespace-nowrap text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-file-word mr-1.5 text-[9px] sm:text-xs"></i> Export Word
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <div class="print-header">
        <div class="print-header-top">
            <div>
                <h1>MSU-IIT Office of Guidance and Counseling</h1>
                <p class="subtitle">Counseling Analytics Report</p>
            </div>
            <div class="meta-block">
                <div>Generated: {{ now()->format('F j, Y') }}</div>
                <div>{{ now()->format('g:i A') }}</div>
            </div>
        </div>
        <div class="meta-row">
            <span><strong>Scope:</strong>
                @if($collegeId) {{ $colleges->firstWhere('id',$collegeId)?->name ?? 'All Colleges' }}
                @else All Colleges @endif
            </span>
            <span><strong>Period:</strong>
                @if($dateFrom && $dateTo) {{ \Carbon\Carbon::parse($dateFrom)->format('M j, Y') }} – {{ \Carbon\Carbon::parse($dateTo)->format('M j, Y') }}
                @else Year {{ $year }} @endif
            </span>
            <span><strong>Prepared by:</strong> {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="filter-bar no-print">
        <form method="GET" action="{{ route('admin.analytics') }}">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 items-end">
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--text-secondary)">College</label>
                    <select name="college_id">
                        <option value="">All Colleges</option>
                        @foreach($colleges as $c)
                            <option value="{{ $c->id }}" {{ $collegeId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--text-secondary)">Year</label>
                    <select name="year">
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--text-secondary)">Date From</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--text-secondary)">Date To</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1 opacity-0">Apply</label>
                    <button type="submit" class="btn-maroon w-full">
                        <i class="fas fa-magnifying-glass mr-1"></i> Apply
                    </button>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1 opacity-0">Reset</label>
                    <a href="{{ route('admin.analytics') }}" class="btn-outline w-full flex items-center justify-center">
                        <i class="fas fa-rotate-left mr-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Print-only summary table (replaces stat cards in print) --}}
    <div class="print-only" style="display:none;margin-bottom:0.8rem;">
        <div class="section-title" style="display:block;">Summary Statistics</div>
        <table style="width:100%;border-collapse:collapse;font-size:8.5pt;">
            <thead>
                <tr style="background:#f5f0eb;">
                    <th style="padding:0.35rem 0.6rem;text-align:left;border:0.5pt solid #d4c4bc;color:#3a0c0c;font-weight:700;">Metric</th>
                    <th style="padding:0.35rem 0.6rem;text-align:right;border:0.5pt solid #d4c4bc;color:#3a0c0c;font-weight:700;">Value</th>
                    <th style="padding:0.35rem 0.6rem;text-align:left;border:0.5pt solid #d4c4bc;color:#3a0c0c;font-weight:700;">Metric</th>
                    <th style="padding:0.35rem 0.6rem;text-align:right;border:0.5pt solid #d4c4bc;color:#3a0c0c;font-weight:700;">Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;">Total Appointments</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;text-align:right;font-weight:700;">{{ number_format($totalAppointments) }}</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;">Completion Rate</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;text-align:right;font-weight:700;">{{ $completionRate }}%</td>
                </tr>
                <tr style="background:#faf8f5;">
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;">Students Served</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;text-align:right;font-weight:700;">{{ number_format($totalStudents) }}</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;">No-Show Rate</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;text-align:right;font-weight:700;">{{ $noShowRate }}%</td>
                </tr>
                <tr>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;">Completed Sessions</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;text-align:right;font-weight:700;">{{ number_format($completedCount) }}</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;">Avg Satisfaction</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;text-align:right;font-weight:700;">{{ $avgSatisfaction !== null ? $avgSatisfaction.'/5' : 'N/A' }}</td>
                </tr>
                <tr style="background:#faf8f5;">
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;">Pending / Approved</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;text-align:right;font-weight:700;">{{ number_format($pendingCount) }}</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;">Follow-ups Due</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;text-align:right;font-weight:700;">{{ number_format($followUpCount) }}</td>
                </tr>
                <tr>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;">Total Referrals</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;text-align:right;font-weight:700;">{{ number_format($referralCount) }}</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;">Avg Counselor Load</td>
                    <td style="padding:0.3rem 0.6rem;border:0.5pt solid #e5e0db;text-align:right;font-weight:700;">{{ $avgCounselorLoad }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Summary cards (screen only) --}}
    <div class="print-summary" style="margin-bottom:1.5rem;">
        <h2 style="font-size:13pt;font-weight:600;color:#7a2a2a;margin-bottom:0.75rem;display:none;" class="print-only">Executive Summary</h2>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="stat-icon maroon"><i class="fas fa-calendar-check"></i></div>
            <div>
                <div class="stat-value">{{ number_format($totalAppointments) }}</div>
                <div class="stat-label">Total Appointments</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon gold"><i class="fas fa-user-graduate"></i></div>
            <div>
                <div class="stat-value">{{ number_format($totalStudents) }}</div>
                <div class="stat-label">Students Served</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
            <div>
                <div class="stat-value">{{ number_format($completedCount) }}</div>
                <div class="stat-label">Completed Sessions</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <div class="stat-value">{{ number_format($pendingCount) }}</div>
                <div class="stat-label">Pending / Approved</div>
            </div>
        </div>
    </div>

    {{-- Rate & insight cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="stat-card" style="flex-direction:column;align-items:flex-start;gap:0.5rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;width:100%;">
                <div class="stat-icon" style="background:#ecfdf5;color:#2d7a4f;border:1px solid #d1fae5;"><i class="fas fa-chart-pie"></i></div>
                <div>
                    <div class="stat-value" style="color:#2d7a4f;font-weight:600;">{{ $completionRate }}%</div>
                    <div class="stat-label">Completion Rate</div>
                </div>
            </div>
            <div style="width:100%;height:5px;border-radius:999px;background:#e5e0db;overflow:hidden;">
                <div style="height:100%;border-radius:999px;width:{{ $completionRate }}%;background:#2d7a4f;transition:width 0.6s;"></div>
            </div>
        </div>
        <div class="stat-card" style="flex-direction:column;align-items:flex-start;gap:0.5rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;width:100%;">
                <div class="stat-icon" style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;"><i class="fas fa-user-xmark"></i></div>
                <div>
                    <div class="stat-value" style="color:#b91c1c;font-weight:600;">{{ $noShowRate }}%</div>
                    <div class="stat-label">No-Show Rate</div>
                </div>
            </div>
            <div style="width:100%;height:5px;border-radius:999px;background:#e5e0db;overflow:hidden;">
                <div style="height:100%;border-radius:999px;width:{{ $noShowRate }}%;background:#dc2626;transition:width 0.6s;"></div>
            </div>
        </div>
        <div class="stat-card" style="flex-direction:column;align-items:flex-start;gap:0.5rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;width:100%;">
                <div class="stat-icon" style="background:#f5e6e8;color:#5c1a1a;border:1px solid #e5d0d3;"><i class="fas fa-share-nodes"></i></div>
                <div>
                    <div class="stat-value" style="color:#5c1a1a;font-weight:600;">{{ number_format($referralCount) }}</div>
                    <div class="stat-label">Referrals</div>
                </div>
            </div>
            <div style="width:100%;height:5px;border-radius:999px;background:#e5e0db;overflow:hidden;">
                <div style="height:100%;border-radius:999px;width:{{ $totalAppointments > 0 ? round(($referralCount/$totalAppointments)*100) : 0 }}%;background:#7a2a2a;transition:width 0.6s;"></div>
            </div>
        </div>
        <div class="stat-card" style="flex-direction:column;align-items:flex-start;gap:0.5rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;width:100%;">
                <div class="stat-icon" style="background:#fef9e7;color:#c9a227;border:1px solid #f5e6b8;"><i class="fas fa-star"></i></div>
                <div>
                    <div class="stat-value" style="color:#c9a227;font-weight:600;">{{ $avgSatisfaction !== null ? $avgSatisfaction.'/5' : 'N/A' }}</div>
                    <div class="stat-label">Avg Satisfaction</div>
                </div>
            </div>
            @php $satPct = $avgSatisfaction !== null ? ($avgSatisfaction/5)*100 : 0; @endphp
            <div style="width:100%;height:5px;border-radius:999px;background:#e5e0db;overflow:hidden;">
                <div style="height:100%;border-radius:999px;width:{{ $satPct }}%;background:#c9a227;transition:width 0.6s;"></div>
            </div>
        </div>
        <div class="stat-card" style="flex-direction:column;align-items:flex-start;gap:0.5rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;width:100%;">
                <div class="stat-icon" style="background:#f5e6e8;color:#5c1a1a;border:1px solid #e5d0d3;"><i class="fas fa-rotate-right"></i></div>
                <div>
                    <div class="stat-value" style="color:#5c1a1a;font-weight:600;">{{ number_format($followUpCount) }}</div>
                    <div class="stat-label">Follow-ups Due</div>
                </div>
            </div>
            <div style="width:100%;height:5px;border-radius:999px;background:#e5e0db;overflow:hidden;">
                <div style="height:100%;border-radius:999px;width:60%;background:#7a2a2a;transition:width 0.6s;"></div>
            </div>
        </div>
    </div>

    {{-- Referral Overview --}}
    <div class="analytics-card">
        <div class="section-title"><i class="fas fa-arrow-right-arrow-left"></i> Referral Overview</div>

        {{-- Print-only referral summary table --}}
        <table class="print-referral-table" style="display:none;margin-bottom:0.6rem;">
            <thead><tr>
                <th>Category</th><th class="val">Students</th><th class="pct">% of Served</th>
            </tr></thead>
            <tbody>
                <tr><td>Inbound Referral (received via referral)</td><td class="val">{{ number_format($referredInStudents) }}</td><td class="pct">{{ $referredInRate }}%</td></tr>
                <tr><td>Outbound Referral (sent to another counselor)</td><td class="val">{{ number_format($referredOutStudents) }}</td><td class="pct">{{ $referredOutRate }}%</td></tr>
            </tbody>
        </table>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Inbound Referral --}}
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide mb-3" style="color:var(--text-secondary);">
                    <i class="fas fa-arrow-right mr-1" style="color:#2d7a4f"></i>Inbound Referral (Students Received via Referral)
                </div>
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.75rem;">
                    <div style="background:rgba(45,122,79,0.1);border-radius:0.75rem;padding:0.75rem 1rem;text-align:center;min-width:5rem;">
                        <div style="font-size:1.6rem;font-weight:800;color:#2d7a4f;line-height:1;">{{ number_format($referredInStudents) }}</div>
                        <div style="font-size:0.7rem;color:var(--text-secondary);margin-top:0.2rem;">students</div>
                    </div>
                    <div>
                        <div style="font-size:1.1rem;font-weight:700;color:var(--text-primary);">{{ $referredInRate }}% of students served</div>
                        <div style="font-size:0.78rem;color:var(--text-secondary);margin-top:0.15rem;">{{ number_format($referredInCount) }} total inbound referral appointments</div>
                    </div>
                </div>
                <div style="height:6px;border-radius:999px;background:var(--border-soft);overflow:hidden;">
                    <div style="height:100%;border-radius:999px;width:{{ min($referredInRate,100) }}%;background:linear-gradient(90deg,#2d7a4f,#17a2b8);transition:width 0.6s;"></div>
                </div>
            </div>
            {{-- Outbound Referral --}}
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide mb-3" style="color:var(--text-secondary);">
                    <i class="fas fa-arrow-left mr-1" style="color:#7a2a2a"></i>Outbound Referral (Students Referred to Another Counselor)
                </div>
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.75rem;">
                    <div style="background:rgba(122,42,42,0.08);border-radius:0.75rem;padding:0.75rem 1rem;text-align:center;min-width:5rem;">
                        <div style="font-size:1.6rem;font-weight:800;color:#7a2a2a;line-height:1;">{{ number_format($referredOutStudents) }}</div>
                        <div style="font-size:0.7rem;color:var(--text-secondary);margin-top:0.2rem;">students</div>
                    </div>
                    <div>
                        <div style="font-size:1.1rem;font-weight:700;color:var(--text-primary);">{{ $referredOutRate }}% of students served</div>
                        <div style="font-size:0.78rem;color:var(--text-secondary);margin-top:0.15rem;">{{ number_format($referredOutCount) }} total outbound referral appointments</div>
                    </div>
                </div>
                <div style="height:6px;border-radius:999px;background:var(--border-soft);overflow:hidden;">
                    <div style="height:100%;border-radius:999px;width:{{ min($referredOutRate,100) }}%;background:linear-gradient(90deg,#7a2a2a,#d4af37);transition:width 0.6s;"></div>
                </div>
            </div>
        </div>
        @if(!empty($referredByData))
        <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border-soft);">
            <div class="text-xs font-semibold uppercase tracking-wide mb-3" style="color:var(--text-secondary);">
                <i class="fas fa-user-tag mr-1"></i>Source of Referral (Referred)
            </div>
            @php $rbTotal = array_sum($referredByData); $rbMax = max(1, max($referredByData)); @endphp
            <div class="space-y-2">
                @foreach($referredByData as $source => $count)
                <div class="progress-bar-wrap">
                    <span class="progress-bar-label" style="min-width:9rem;">{{ ucwords(str_replace('_',' ',$source)) }}</span>
                    <div class="progress-bar-track">
                        <div class="progress-bar-fill" style="width:{{ ($count/$rbMax)*100 }}%;background:#7a2a2a;"></div>
                    </div>
                    <span class="progress-bar-count">{{ $count }}</span>
                    <span style="font-size:0.75rem;color:var(--text-secondary);min-width:3rem;text-align:right;">{{ round(($count/$rbTotal)*100,1) }}%</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @if(!empty($referredToData))
        <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border-soft);">
            <div class="text-xs font-semibold uppercase tracking-wide mb-3" style="color:var(--text-secondary);">
                <i class="fas fa-arrow-up-right-from-square mr-1"></i>Referred Out (External Professional / Service)
                <span style="margin-left:0.5rem;font-size:0.7rem;font-weight:500;color:var(--text-muted);">{{ $referredToCount }} total</span>
            </div>
            @php $rtTotal = array_sum($referredToData); $rtMax = max(1, max($referredToData)); @endphp
            <div class="space-y-2">
                @foreach($referredToData as $destination => $count)
                <div class="progress-bar-wrap">
                    <span class="progress-bar-label" style="min-width:9rem;">{{ ucwords(str_replace('_',' ',$destination)) }}</span>
                    <div class="progress-bar-track">
                        <div class="progress-bar-fill" style="width:{{ ($count/$rtMax)*100 }}%;background:#2a5a7a;"></div>
                    </div>
                    <span class="progress-bar-count">{{ $count }}</span>
                    <span style="font-size:0.75rem;color:var(--text-secondary);min-width:3rem;text-align:right;">{{ round(($count/$rtTotal)*100,1) }}%</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="analytics-card">
            <div class="section-title"><i class="fas fa-tag"></i> Appointment Status Breakdown</div>
            @php $hasStatus = collect($statusData)->sum('count') > 0; @endphp
            @if($hasStatus)
                <div class="chart-wrap" style="height:260px">
                    <canvas id="statusChart"></canvas>
                </div>
            @else
                <div class="empty-state"><i class="fas fa-chart-pie"></i>No data for this period.</div>
            @endif
        </div>
        <div class="analytics-card">
            <div class="section-title"><i class="fas fa-bookmark"></i> Booking Type Distribution</div>
            @php $hasBooking = array_sum($bookingTypeData) > 0; @endphp
            @if($hasBooking)
                <div class="chart-wrap" style="height:260px">
                    <canvas id="bookingChart"></canvas>
                </div>
            @else
                <div class="empty-state"><i class="fas fa-bookmark"></i>No data for this period.</div>
            @endif
        </div>
    </div>

    {{-- Monthly counseling availed --}}
    <div class="analytics-card">
        <div class="section-title"><i class="fas fa-chart-line"></i> Monthly Counseling Availed</div>
        @php $hasMonthly = collect($monthlyData)->sum('count') > 0; @endphp
        @if($hasMonthly)
            <div class="chart-wrap" style="height:280px">
                <canvas id="monthlyChart"></canvas>
            </div>
        @else
            <div class="empty-state"><i class="fas fa-chart-line"></i>No completed/approved sessions found for {{ $year }}.</div>
        @endif
    </div>

    {{-- Row 2: Per-college + Status detail list --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="analytics-card">
            <div class="section-title"><i class="fas fa-building-columns"></i> Appointments per College</div>
            @php $hasCollege = array_sum($collegeAppointmentCounts) > 0; @endphp
            @if($hasCollege)
                <div class="chart-wrap" style="height:300px">
                    <canvas id="collegeChart"></canvas>
                </div>
            @else
                <div class="empty-state"><i class="fas fa-building-columns"></i>No data for this period.</div>
            @endif
        </div>
        <div class="analytics-card">
            <div class="section-title"><i class="fas fa-list"></i> Status Detail</div>
            @php
                $statusColors = [
                    'pending'              => ['bar'=>'#f0c040'],
                    'approved'             => ['bar'=>'#2d7a4f'],
                    'completed'            => ['bar'=>'#2a5a7a'],
                    'cancelled'            => ['bar'=>'#dc3545'],
                    'rejected'             => ['bar'=>'#c0392b'],
                    'referred'             => ['bar'=>'#7a2a2a'],
                    'rescheduled'          => ['bar'=>'#17a2b8'],
                    'reschedule_requested' => ['bar'=>'#e67e22'],
                    'reschedule_rejected'  => ['bar'=>'#e74c3c'],
                ];
                $maxCount = max(1, collect($statusData)->max('count'));
            @endphp
            @forelse($statusData as $s)
                @if($s['count'] > 0)
                <div class="progress-bar-wrap">
                    <span class="progress-bar-label">{{ $s['label'] }}</span>
                    <div class="progress-bar-track">
                        <div class="progress-bar-fill"
                             style="width:{{ ($s['count']/$maxCount)*100 }}%;background:{{ $statusColors[$s['key']]['bar'] ?? '#7a2a2a' }}">
                        </div>
                    </div>
                    <span class="progress-bar-count">{{ $s['count'] }}</span>
                </div>
                @endif
            @empty
                <div class="empty-state"><i class="fas fa-list"></i>No data.</div>
            @endforelse
        </div>
    </div>

    {{-- Print signature section --}}
    <div class="print-signature">
        <div class="print-sig-grid">
            <div class="print-sig-item">
                <div>Prepared by:</div>
                <div class="print-sig-line">
                    <div class="print-sig-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                    <div class="print-sig-role">Guidance Counselor / Admin</div>
                </div>
            </div>
            <div class="print-sig-item">
                <div>Noted by:</div>
                <div class="print-sig-line">
                    <div class="print-sig-name">&nbsp;</div>
                    <div class="print-sig-role">Head, Guidance Office</div>
                </div>
            </div>
            <div class="print-sig-item">
                <div>Approved by:</div>
                <div class="print-sig-line">
                    <div class="print-sig-name">&nbsp;</div>
                    <div class="print-sig-role">Director / Dean of Student Affairs</div>
                </div>
            </div>
        </div>
        <p style="font-size:7pt;color:#8b7e76;margin-top:0.8rem;font-style:italic;">
            This report is generated from the MSU-IIT Office of Guidance and Counseling Information System. All data is confidential and for official use only.
        </p>
    </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
    Chart.defaults.color = '#6b5e57';

    const maroon  = '#7a2a2a';
    const gold    = '#d4af37';
    const palette = [
        '#7a2a2a','#d4af37','#2d7a4f','#2a5a7a','#7c3aed',
        '#e67e22','#17a2b8','#e74c3c','#c0392b','#1abc9c'
    ];

    @php
        $statusLabels = collect($statusData)->where('count','>',0)->pluck('label')->values();
        $statusCounts = collect($statusData)->where('count','>',0)->pluck('count')->values();
    @endphp
    @if(collect($statusData)->sum('count') > 0)
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($statusLabels),
            datasets: [{ data: @json($statusCounts), backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, padding: 14, font: { size: 12 } } },
                tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} (${((ctx.parsed/ctx.dataset.data.reduce((a,b)=>a+b,0))*100).toFixed(1)}%)` } }
            },
            cutout: '62%',
        }
    });
    @endif

    @php $btLabels = array_keys($bookingTypeData); $btCounts = array_values($bookingTypeData); @endphp
    @if(array_sum($bookingTypeData) > 0)
    new Chart(document.getElementById('bookingChart'), {
        type: 'pie',
        data: {
            labels: @json($btLabels),
            datasets: [{ data: @json($btCounts), backgroundColor: [maroon, gold, '#2d7a4f', '#2a5a7a', '#7c3aed'], borderWidth: 2, borderColor: '#fff' }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'right', labels: { boxWidth: 12, padding: 14, font: { size: 12 } } } }
        }
    });
    @endif

    @php $mLabels = collect($monthlyData)->pluck('month'); $mCounts = collect($monthlyData)->pluck('count'); @endphp
    @if(collect($monthlyData)->sum('count') > 0)
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: @json($mLabels),
            datasets: [
                { label: 'Sessions Availed', data: @json($mCounts), backgroundColor: 'rgba(122,42,42,0.15)', borderColor: maroon, borderWidth: 2, borderRadius: 6, borderSkipped: false },
                { label: 'Trend', data: @json($mCounts), type: 'line', borderColor: gold, backgroundColor: 'transparent', borderWidth: 2.5, pointBackgroundColor: gold, pointRadius: 4, tension: 0.4 }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { boxWidth: 12, padding: 16 } } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
    @endif

    @php
        $cLabels = array_keys($collegeAppointmentCounts);
        $cCounts = array_values($collegeAppointmentCounts);
        $cShort  = array_map(fn($n) => strlen($n) > 30 ? substr($n,0,28).'…' : $n, $cLabels);
    @endphp
    @if(array_sum($collegeAppointmentCounts) > 0)
    new Chart(document.getElementById('collegeChart'), {
        type: 'bar',
        data: {
            labels: @json($cShort),
            datasets: [{ label: 'Appointments', data: @json($cCounts), backgroundColor: palette, borderRadius: 5, borderSkipped: false }]
        },
        options: {
            indexAxis: 'y',
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                y: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });
    @endif

});

function exportWord() {
    let charts = [];
    document.querySelectorAll('canvas').forEach(canvas => {
        let tempCanvas = document.createElement('canvas');
        tempCanvas.width = canvas.width;
        tempCanvas.height = canvas.height;
        let ctx = tempCanvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
        ctx.drawImage(canvas, 0, 0);
        
        let clientWidth = canvas.clientWidth || 500;
        let clientHeight = canvas.clientHeight || 300;
        
        let base64 = tempCanvas.toDataURL("image/jpeg", 1.0).split(',')[1];
        
        charts.push({
            id: canvas.id, 
            base64: base64,
            width: clientWidth,
            height: clientHeight
        });
    });

    let htmlContent = `
        <style>
            @page { mso-page-orientation: portrait; margin: 1in; }
            body { font-family: 'Calibri', sans-serif; background: #ffffff; color: #000000; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10pt; }
            th, td { border: 1px solid #d4c4bc; padding: 6px; text-align: left; }
            th { background-color: #f5f0eb; font-weight: bold; color: #3a0c0c; }
            h1 { font-size: 16pt; color: #3a0c0c; margin-bottom: 5px; }
            h2 { font-size: 13pt; color: #7a2a2a; border-bottom: 1px solid #c9a227; padding-bottom: 4px; margin-top: 25px; margin-bottom: 15px; }
            .meta { font-size: 10pt; margin-bottom: 25px; color: #444444; line-height: 1.5; }
            .chart-img { text-align: center; margin: 20px 0; }
            .sig-table { width: 100%; border: none; margin-top: 50px; }
            .sig-table td { border: none; padding: 10px; text-align: center; vertical-align: bottom; height: 80px; }
            .sig-line { border-top: 1px solid #000000; width: 85%; margin: 0 auto; padding-top: 5px; font-weight: bold; font-size: 10pt; color: #000; }
            .sig-role { font-weight: normal; font-size: 8pt; color: #555; }
        </style>
        <h1>MSU-IIT Office of Guidance and Counseling</h1>
        <div class="meta">
            <strong>Counseling Analytics Report</strong><br>
            Generated: {{ now()->format('F j, Y g:i A') }}<br>
            Prepared by: {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}<br>
            Scope: @if($collegeId) {{ addslashes($colleges->firstWhere('id',$collegeId)?->name ?? 'All Colleges') }} @else All Colleges @endif<br>
            Period: @if($dateFrom && $dateTo) {{ \Carbon\Carbon::parse($dateFrom)->format('M j, Y') }} – {{ \Carbon\Carbon::parse($dateTo)->format('M j, Y') }} @else Year {{ $year }} @endif
        </div>
        
        <h2>Summary Statistics</h2>
        <table>
            <tr>
                <th>Total Appointments</th><td>{{ number_format($totalAppointments) }}</td>
                <th>Completion Rate</th><td>{{ $completionRate }}%</td>
            </tr>
            <tr>
                <th>Students Served</th><td>{{ number_format($totalStudents) }}</td>
                <th>No-Show Rate</th><td>{{ $noShowRate }}%</td>
            </tr>
            <tr>
                <th>Completed Sessions</th><td>{{ number_format($completedCount) }}</td>
                <th>Avg Satisfaction</th><td>{{ $avgSatisfaction !== null ? $avgSatisfaction.'/5' : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Pending / Approved</th><td>{{ number_format($pendingCount) }}</td>
                <th>Follow-ups Due</th><td>{{ number_format($followUpCount) }}</td>
            </tr>
            <tr>
                <th>Total Referrals</th><td>{{ number_format($referralCount) }}</td>
                <th>Avg Counselor Load</th><td>{{ $avgCounselorLoad }}</td>
            </tr>
        </table>
        
        <h2>Referral Overview</h2>
        <table>
            <tr><th>Category</th><th>Students</th><th>% of Served</th></tr>
            <tr><td>Inbound Referral (received)</td><td>{{ number_format($referredInStudents) }}</td><td>{{ $referredInRate }}%</td></tr>
            <tr><td>Outbound Referral (sent)</td><td>{{ number_format($referredOutStudents) }}</td><td>{{ $referredOutRate }}%</td></tr>
        </table>
        
        <h2>Feedback Details</h2>
        <p style="margin-top: 15px; font-size: 10pt; line-height: 1.5; color: #444444;">
            <strong>Average Satisfaction Rating:</strong> {{ $avgSatisfaction !== null ? $avgSatisfaction.'/5' : 'No ratings yet.' }}
        </p>
    `;

    let chartDataTables = {
        'statusChart': `
            <p style="margin-top: 15px; font-size: 10pt; line-height: 1.5; color: #444444;">
                <strong>Data Summary:</strong> The Appointment Status Breakdown shows 
                @php
                    $statusStrings = [];
                    foreach($statusData as $s) {
                        if($s['count'] > 0) {
                            $statusStrings[] = $s['count'] . " " . strtolower($s['label']);
                        }
                    }
                    echo empty($statusStrings) ? "no appointments." : implode(', ', $statusStrings) . ".";
                @endphp
            </p>
        `,
        'bookingChart': `
            <p style="margin-top: 15px; font-size: 10pt; line-height: 1.5; color: #444444;">
                <strong>Data Summary:</strong> The Booking Type Distribution consists of 
                @php
                    $bookingStrings = [];
                    foreach($bookingTypeData as $type => $count) {
                        if($count > 0) {
                            $bookingStrings[] = $count . " " . strtolower($type) . " bookings";
                        }
                    }
                    echo empty($bookingStrings) ? "no bookings." : implode(' and ', $bookingStrings) . ".";
                @endphp
            </p>
        `,
        'monthlyChart': `
            <p style="margin-top: 15px; font-size: 10pt; line-height: 1.5; color: #444444;">
                <strong>Data Summary:</strong> Monthly Counseling Availed records 
                @php
                    $monthlyStrings = [];
                    foreach($monthlyData as $m) {
                        if(data_get($m, 'count') > 0) {
                            $monthlyStrings[] = data_get($m, 'count') . " sessions in " . data_get($m, 'month');
                        }
                    }
                    echo empty($monthlyStrings) ? "no sessions." : implode(', ', $monthlyStrings) . ".";
                @endphp
            </p>
        `,
        'collegeChart': `
            <p style="margin-top: 15px; font-size: 10pt; line-height: 1.5; color: #444444;">
                <strong>Data Summary:</strong> Appointments per College are distributed as follows: 
                @php
                    $collegeStrings = [];
                    foreach($collegeAppointmentCounts as $college => $count) {
                        if($count > 0) {
                            $collegeStrings[] = $count . " from " . $college;
                        }
                    }
                    echo empty($collegeStrings) ? "none." : implode(', ', $collegeStrings) . ".";
                @endphp
            </p>
        `
    };

    charts.forEach(c => {
        let title = '';
        if (c.id === 'statusChart') title = 'Appointment Status Breakdown';
        else if (c.id === 'bookingChart') title = 'Booking Type Distribution';
        else if (c.id === 'monthlyChart') title = 'Monthly Counseling Availed';
        else if (c.id === 'collegeChart') title = 'Appointments per College';
        
        htmlContent += "<h2>" + title + "</h2>";
        htmlContent += '<div class="chart-img"><img src="file:///C:/fake/' + c.id + '.jpg" width="' + c.width + '" height="' + c.height + '"></div>';
        
        if (chartDataTables[c.id]) {
            htmlContent += chartDataTables[c.id];
        }
    });

    htmlContent += `
        <table class="sig-table">
            <tr>
                <td><div class="sig-line">{{ addslashes(auth()->user()->first_name) }} {{ addslashes(auth()->user()->last_name) }}<br><span class="sig-role">Guidance Counselor / Admin</span></div></td>
                <td><div class="sig-line">&nbsp;<br><span class="sig-role">Head, Guidance Office</span></div></td>
                <td><div class="sig-line">&nbsp;<br><span class="sig-role">Director / Dean of Student Affairs</span></div></td>
            </tr>
        </table>
        <p style="font-size:8pt;color:#666666;text-align:center;margin-top:20px;">This report is generated from the MSU-IIT Office of Guidance and Counseling Information System. All data is confidential and for official use only.</p>
    `;

    let boundary = "mht_boundary_123456789";
    
    let mhtml = "MIME-Version: 1.0\r\n";
    mhtml += 'Content-Type: multipart/related; boundary="' + boundary + '"\r\n\r\n';
    
    // HTML Part
    mhtml += "--" + boundary + "\r\n";
    mhtml += "Content-Location: file:///C:/fake/document.html\r\n";
    mhtml += "Content-Transfer-Encoding: utf-8\r\n";
    mhtml += "Content-Type: text/html; charset=\"utf-8\"\r\n\r\n";
    
    mhtml += "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word'>\n";
    mhtml += "<head>\n<meta charset='utf-8'>\n<title>Analytics Report</title>\n</head>\n<body>\n";
    mhtml += htmlContent;
    mhtml += "\n</body>\n</html>\r\n\r\n";

    // Image Parts
    charts.forEach(c => {
        mhtml += "--" + boundary + "\r\n";
        mhtml += "Content-Location: file:///C:/fake/" + c.id + ".jpg\r\n";
        mhtml += "Content-Transfer-Encoding: base64\r\n";
        mhtml += "Content-Type: image/jpeg\r\n\r\n";
        mhtml += c.base64 + "\r\n\r\n";
    });

    mhtml += "--" + boundary + "--";

    let blob = new Blob([mhtml], { type: 'application/msword' });
    let url = URL.createObjectURL(blob);
    let downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);
    if (navigator.msSaveOrOpenBlob) {
        navigator.msSaveOrOpenBlob(blob, 'Analytics_Report.doc');
    } else {
        downloadLink.href = url;
        downloadLink.download = 'Analytics_Report.doc';
        downloadLink.click();
    }
    document.body.removeChild(downloadLink);
    URL.revokeObjectURL(url);
}
</script>
@endpush
