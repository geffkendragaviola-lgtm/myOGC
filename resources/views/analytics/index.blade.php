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
}
.stat-icon {
    width: 3rem; height: 3rem;
    border-radius: 0.75rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.stat-icon.maroon { background: linear-gradient(135deg,#7a2a2a,#5c1a1a); color:#fff; }
.stat-icon.gold   { background: linear-gradient(135deg,#d4af37,#c9a227); color:#3a0c0c; }
.stat-icon.green  { background: linear-gradient(135deg,#2d7a4f,#1e5c38); color:#fff; }
.stat-icon.blue   { background: linear-gradient(135deg,#2a5a7a,#1a3c5c); color:#fff; }
.stat-value { font-size: 1.75rem; font-weight: 800; color: var(--text-primary); line-height:1; }
.stat-label { font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.2rem; }
.section-title {
    font-size: 1rem; font-weight: 700; color: var(--text-primary);
    margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;
}
.section-title i { color: var(--maroon-soft); }
.filter-bar {
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: 1rem;
    padding: 1rem 1.25rem;
    box-shadow: 0 2px 10px rgba(44,36,32,0.05);
}
.filter-bar select, .filter-bar input[type=date] {
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
.progress-bar-count { font-size: 0.8rem; font-weight: 700; color: var(--text-primary); min-width: 2.5rem; text-align: right; }

/* Print styles */
@media print {
    .no-print { display: none !important; }
    body { background: #fff !important; }
    .ogc-navbar, #ogcSidebar { display: none !important; }
    #ogcMainContent { margin-left: 0 !important; padding-top: 0 !important; }
    .analytics-card, .stat-card { box-shadow: none !important; border: 1px solid #ddd !important; break-inside: avoid; }
    .print-header { display: block !important; }
    canvas { max-width: 100% !important; }
}
.print-header { display: none; }
</style>
@endpush

@section('content')
<div class="p-6 space-y-6">

    {{-- Print header (hidden on screen) --}}
    <div class="print-header mb-4">
        <h1 style="font-size:1.4rem;font-weight:800;color:#3a0c0c;">MSU-IIT Office of Guidance and Counseling</h1>
        <p style="font-size:0.9rem;color:#6b5e57;">Analytics Report &mdash; Generated {{ now()->format('F j, Y g:i A') }}</p>
        @if($collegeId)
            <p style="font-size:0.85rem;color:#6b5e57;">College: {{ $colleges->firstWhere('id',$collegeId)?->name ?? 'All Colleges' }}</p>
        @endif
        <hr style="margin:0.75rem 0;border-color:#e5e0db;">
    </div>

    {{-- Page header --}}
    <div class="flex flex-wrap items-center justify-between gap-3 no-print">
        <div>
            <h1 class="text-xl font-bold" style="color:var(--text-primary)">
                <i class="fas fa-chart-bar mr-2" style="color:var(--maroon-soft)"></i>Analytics
            </h1>
            <p class="text-sm mt-0.5" style="color:var(--text-secondary)">Appointment and counseling insights</p>
        </div>
        <button onclick="window.print()" class="btn-outline no-print">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>

    {{-- Filter bar --}}
    <div class="filter-bar no-print">
        <form method="GET" action="{{ route('admin.analytics') }}" class="flex flex-wrap gap-3 items-end">
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
            <button type="submit" class="btn-maroon">
                <i class="fas fa-filter mr-1"></i> Apply
            </button>
            <a href="{{ route('admin.analytics') }}" class="btn-outline">
                <i class="fas fa-times"></i> Reset
            </a>
        </form>
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
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
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ number_format($completedCount) }}</div>
                <div class="stat-label">Completed Sessions</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-value">{{ number_format($pendingCount) }}</div>
                <div class="stat-label">Pending / Approved</div>
            </div>
        </div>
    </div>

    {{-- Row 1: Status chart + Booking type --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Status breakdown --}}
        <div class="analytics-card">
            <div class="section-title"><i class="fas fa-tags"></i> Appointment Status Breakdown</div>
            @php $hasStatus = collect($statusData)->sum('count') > 0; @endphp
            @if($hasStatus)
                <div class="chart-wrap" style="height:260px">
                    <canvas id="statusChart"></canvas>
                </div>
            @else
                <div class="empty-state"><i class="fas fa-chart-pie"></i>No data for this period.</div>
            @endif
        </div>

        {{-- Booking type --}}
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

        {{-- Per-college bar chart --}}
        <div class="analytics-card">
            <div class="section-title"><i class="fas fa-university"></i> Appointments per College</div>
            @php $hasCollege = array_sum($collegeAppointmentCounts) > 0; @endphp
            @if($hasCollege)
                <div class="chart-wrap" style="height:300px">
                    <canvas id="collegeChart"></canvas>
                </div>
            @else
                <div class="empty-state"><i class="fas fa-university"></i>No data for this period.</div>
            @endif
        </div>

        {{-- Status detail list --}}
        <div class="analytics-card">
            <div class="section-title"><i class="fas fa-list-ul"></i> Status Detail</div>
            @php
                $statusColors = [
                    'pending'              => ['bg'=>'#fff3cd','text'=>'#856404','bar'=>'#f0c040'],
                    'approved'             => ['bg'=>'#d1e7dd','text'=>'#0f5132','bar'=>'#2d7a4f'],
                    'completed'            => ['bg'=>'#cfe2ff','text'=>'#084298','bar'=>'#2a5a7a'],
                    'cancelled'            => ['bg'=>'#f8d7da','text'=>'#842029','bar'=>'#dc3545'],
                    'rejected'             => ['bg'=>'#f8d7da','text'=>'#842029','bar'=>'#c0392b'],
                    'referred'             => ['bg'=>'#e2d9f3','text'=>'#432874','bar'=>'#7c3aed'],
                    'rescheduled'          => ['bg'=>'#d1ecf1','text'=>'#0c5460','bar'=>'#17a2b8'],
                    'reschedule_requested' => ['bg'=>'#fde8d8','text'=>'#7d3c0a','bar'=>'#e67e22'],
                    'reschedule_rejected'  => ['bg'=>'#f8d7da','text'=>'#842029','bar'=>'#e74c3c'],
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

    // ── Status doughnut ──────────────────────────────────────────────
    @php
        $statusLabels = collect($statusData)->where('count','>',0)->pluck('label')->values();
        $statusCounts = collect($statusData)->where('count','>',0)->pluck('count')->values();
    @endphp
    @if(collect($statusData)->sum('count') > 0)
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($statusLabels),
            datasets: [{
                data: @json($statusCounts),
                backgroundColor: palette,
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, padding: 14, font: { size: 12 } } },
                tooltip: { callbacks: {
                    label: ctx => ` ${ctx.label}: ${ctx.parsed} (${((ctx.parsed/ctx.dataset.data.reduce((a,b)=>a+b,0))*100).toFixed(1)}%)`
                }}
            },
            cutout: '62%',
        }
    });
    @endif

    // ── Booking type pie ─────────────────────────────────────────────
    @php
        $btLabels = array_keys($bookingTypeData);
        $btCounts = array_values($bookingTypeData);
    @endphp
    @if(array_sum($bookingTypeData) > 0)
    new Chart(document.getElementById('bookingChart'), {
        type: 'pie',
        data: {
            labels: @json($btLabels),
            datasets: [{
                data: @json($btCounts),
                backgroundColor: [maroon, gold, '#2d7a4f', '#2a5a7a', '#7c3aed'],
                borderWidth: 2, borderColor: '#fff',
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, padding: 14, font: { size: 12 } } },
            }
        }
    });
    @endif

    // ── Monthly line/bar ─────────────────────────────────────────────
    @php
        $mLabels = collect($monthlyData)->pluck('month');
        $mCounts = collect($monthlyData)->pluck('count');
    @endphp
    @if(collect($monthlyData)->sum('count') > 0)
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: @json($mLabels),
            datasets: [{
                label: 'Sessions Availed',
                data: @json($mCounts),
                backgroundColor: 'rgba(122,42,42,0.15)',
                borderColor: maroon,
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            },{
                label: 'Trend',
                data: @json($mCounts),
                type: 'line',
                borderColor: gold,
                backgroundColor: 'transparent',
                borderWidth: 2.5,
                pointBackgroundColor: gold,
                pointRadius: 4,
                tension: 0.4,
            }]
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

    // ── Per-college horizontal bar ───────────────────────────────────
    @php
        $cLabels = array_keys($collegeAppointmentCounts);
        $cCounts = array_values($collegeAppointmentCounts);
        // Shorten college names for chart
        $cShort = array_map(fn($n) => strlen($n) > 30 ? substr($n,0,28).'…' : $n, $cLabels);
    @endphp
    @if(array_sum($collegeAppointmentCounts) > 0)
    new Chart(document.getElementById('collegeChart'), {
        type: 'bar',
        data: {
            labels: @json($cShort),
            datasets: [{
                label: 'Appointments',
                data: @json($cCounts),
                backgroundColor: palette,
                borderRadius: 5,
                borderSkipped: false,
            }]
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
</script>
@endpush
