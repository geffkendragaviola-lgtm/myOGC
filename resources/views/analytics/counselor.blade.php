@extends('layouts.app')

@section('title', 'Analytics — ' . $collegeName)

@push('styles')
<style>
.an-card {
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
    padding: 1.2rem 1.4rem;
    display: flex; align-items: center; gap: 1rem;
}
.stat-icon {
    width: 2.9rem; height: 2.9rem; border-radius: 0.75rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.si-maroon { background: linear-gradient(135deg,#7a2a2a,#5c1a1a); color:#fff; }
.si-gold   { background: linear-gradient(135deg,#d4af37,#c9a227); color:#3a0c0c; }
.si-green  { background: linear-gradient(135deg,#2d7a4f,#1e5c38); color:#fff; }
.si-blue   { background: linear-gradient(135deg,#2a5a7a,#1a3c5c); color:#fff; }
.si-purple { background: linear-gradient(135deg,#6d28d9,#4c1d95); color:#fff; }
.si-red    { background: linear-gradient(135deg,#b91c1c,#991b1b); color:#fff; }
.stat-value { font-size: 1.65rem; font-weight: 800; color: var(--text-primary); line-height:1; }
.stat-label { font-size: 0.78rem; color: var(--text-secondary); margin-top: 0.2rem; }
.sec-title {
    font-size: 0.95rem; font-weight: 700; color: var(--text-primary);
    margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;
}
.sec-title i { color: var(--maroon-soft); }
.filter-bar {
    background: #fff; border: 1px solid var(--border-soft);
    border-radius: 1rem; padding: 1rem 1.25rem;
    box-shadow: 0 2px 10px rgba(44,36,32,0.05);
}
.filter-bar select, .filter-bar input[type=date] {
    border: 1px solid var(--border-soft); border-radius: 0.5rem;
    padding: 0.45rem 0.75rem; font-size: 0.875rem;
    color: var(--text-primary); background: var(--bg-warm); outline: none;
    transition: border-color 0.2s;
}
.filter-bar select:focus, .filter-bar input[type=date]:focus { border-color: var(--maroon-soft); }
.btn-maroon {
    background: linear-gradient(135deg,var(--maroon-soft),var(--maroon-medium));
    color:#fff; border:none; border-radius:0.5rem;
    padding:0.5rem 1.1rem; font-size:0.875rem; font-weight:600;
    cursor:pointer; transition:opacity 0.2s;
}
.btn-maroon:hover { opacity:0.88; }
.btn-outline {
    background:transparent; color:var(--maroon-soft);
    border:1.5px solid var(--maroon-soft); border-radius:0.5rem;
    padding:0.45rem 1rem; font-size:0.875rem; font-weight:600;
    cursor:pointer; transition:all 0.2s; text-decoration:none;
    display:inline-flex; align-items:center; gap:0.4rem;
}
.btn-outline:hover { background:var(--maroon-soft); color:#fff; }
.pb-wrap { display:flex; align-items:center; gap:0.75rem; margin-bottom:0.55rem; }
.pb-label { font-size:0.8rem; color:var(--text-secondary); min-width:8rem; }
.pb-track { flex:1; height:0.5rem; background:var(--border-soft); border-radius:999px; overflow:hidden; }
.pb-fill  { height:100%; border-radius:999px; transition:width 0.6s ease; }
.pb-count { font-size:0.8rem; font-weight:700; color:var(--text-primary); min-width:2.5rem; text-align:right; }
.empty-state { text-align:center; padding:2.5rem 1rem; color:var(--text-muted); font-size:0.9rem; }
.empty-state i { font-size:2.2rem; margin-bottom:0.6rem; opacity:0.3; display:block; }
.college-badge {
    display:inline-flex; align-items:center; gap:0.4rem;
    background:linear-gradient(135deg,var(--maroon-soft),var(--maroon-medium));
    color:#fff; padding:0.3rem 0.85rem; border-radius:999px;
    font-size:0.78rem; font-weight:600;
}

@media print {
    .no-print { display:none !important; }
    body { background:#fff !important; }
    .ogc-navbar, #ogcSidebar { display:none !important; }
    #ogcMainContent { margin-left:0 !important; padding-top:0 !important; }
    .an-card, .stat-card { box-shadow:none !important; border:1px solid #ddd !important; break-inside:avoid; }
    .print-header { display:block !important; }
    canvas { max-width:100% !important; }
    .grid { display:block !important; }
    .an-card { margin-bottom:1rem; }
}
.print-header { display:none; }
</style>
@endpush

@section('content')
<div class="p-6 space-y-6">

    {{-- Print header --}}
    <div class="print-header mb-4">
        <h1 style="font-size:1.3rem;font-weight:800;color:#3a0c0c;">MSU-IIT Office of Guidance and Counseling</h1>
        <h2 style="font-size:1rem;font-weight:700;color:#5c1a1a;margin-top:0.2rem;">{{ $collegeName }}</h2>
        <p style="font-size:0.85rem;color:#6b5e57;">Counselor Analytics Report &mdash; {{ $dateFrom && $dateTo ? $dateFrom.' to '.$dateTo : 'Year '.$year }}</p>
        <p style="font-size:0.82rem;color:#6b5e57;">Prepared by: {{ auth()->user()->first_name }} {{ auth()->user()->last_name }} &mdash; Generated {{ now()->format('F j, Y g:i A') }}</p>
        <hr style="margin:0.75rem 0;border-color:#e5e0db;">
    </div>

    {{-- Page header --}}
    <div class="flex flex-wrap items-start justify-between gap-3 no-print">
        <div>
            <div class="flex flex-wrap items-center gap-2 mb-1">
                <h1 class="text-xl font-bold" style="color:var(--text-primary)">
                    <i class="fas fa-chart-bar mr-2" style="color:var(--maroon-soft)"></i>Analytics
                </h1>
                <span class="college-badge"><i class="fas fa-university text-xs"></i>{{ $collegeName }}</span>
            </div>
            <p class="text-sm" style="color:var(--text-secondary)">Counseling insights for your assigned college(s)</p>
        </div>
        <button onclick="window.print()" class="btn-outline no-print">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>

    {{-- Filter bar --}}
    <div class="filter-bar no-print">
        <form method="GET" action="{{ route('counselor.analytics') }}" class="flex flex-wrap gap-3 items-end">
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
            <button type="submit" class="btn-maroon"><i class="fas fa-filter mr-1"></i>Apply</button>
            <a href="{{ route('counselor.analytics') }}" class="btn-outline"><i class="fas fa-times"></i>Reset</a>
        </form>
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="stat-card">
            <div class="stat-icon si-maroon"><i class="fas fa-calendar-check"></i></div>
            <div><div class="stat-value">{{ number_format($totalAppointments) }}</div><div class="stat-label">Total Appointments</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-gold"><i class="fas fa-user-graduate"></i></div>
            <div><div class="stat-value">{{ number_format($totalStudents) }}</div><div class="stat-label">Students Served</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
            <div><div class="stat-value">{{ number_format($completedCount) }}</div><div class="stat-label">Completed</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-blue"><i class="fas fa-clock"></i></div>
            <div><div class="stat-value">{{ number_format($pendingCount) }}</div><div class="stat-label">Pending / Approved</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-purple"><i class="fas fa-share-square"></i></div>
            <div><div class="stat-value">{{ number_format($referralCount) }}</div><div class="stat-label">Referrals</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-red"><i class="fas fa-times-circle"></i></div>
            <div><div class="stat-value">{{ number_format($cancelledCount) }}</div><div class="stat-label">Cancelled</div></div>
        </div>
    </div>

    {{-- Monthly chart (full width) --}}
    <div class="an-card">
        <div class="sec-title"><i class="fas fa-chart-line"></i>Monthly Students Who Availed Counseling — {{ $year }}</div>
        @if(collect($monthlyData)->sum('count') > 0)
            <div style="position:relative;height:280px"><canvas id="monthlyChart"></canvas></div>
        @else
            <div class="empty-state"><i class="fas fa-chart-line"></i>No completed/approved sessions found for {{ $year }}.</div>
        @endif
    </div>

    {{-- Status doughnut + booking type --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="an-card">
            <div class="sec-title"><i class="fas fa-tags"></i>Appointment Status Breakdown</div>
            @if(collect($statusData)->sum('count') > 0)
                <div style="position:relative;height:260px"><canvas id="statusChart"></canvas></div>
            @else
                <div class="empty-state"><i class="fas fa-chart-pie"></i>No data for this period.</div>
            @endif
        </div>
        <div class="an-card">
            <div class="sec-title"><i class="fas fa-bookmark"></i>Booking Type Distribution</div>
            @if(array_sum($bookingTypeData) > 0)
                <div style="position:relative;height:260px"><canvas id="bookingChart"></canvas></div>
            @else
                <div class="empty-state"><i class="fas fa-bookmark"></i>No data for this period.</div>
            @endif
        </div>
    </div>

    {{-- Status detail bars + top concerns --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Status progress bars --}}
        <div class="an-card">
            <div class="sec-title"><i class="fas fa-list-ul"></i>Status Detail</div>
            @php
                $barColors = [
                    'pending'              => '#f0c040',
                    'approved'             => '#2d7a4f',
                    'completed'            => '#2a5a7a',
                    'cancelled'            => '#dc3545',
                    'rejected'             => '#c0392b',
                    'referred'             => '#7c3aed',
                    'rescheduled'          => '#17a2b8',
                    'reschedule_requested' => '#e67e22',
                    'reschedule_rejected'  => '#e74c3c',
                ];
                $maxCount = max(1, collect($statusData)->max('count'));
            @endphp
            @php $hasAny = collect($statusData)->sum('count') > 0; @endphp
            @if($hasAny)
                @foreach($statusData as $s)
                    @if($s['count'] > 0)
                    <div class="pb-wrap">
                        <span class="pb-label">{{ $s['label'] }}</span>
                        <div class="pb-track">
                            <div class="pb-fill" style="width:{{ ($s['count']/$maxCount)*100 }}%;background:{{ $barColors[$s['key']] ?? '#7a2a2a' }}"></div>
                        </div>
                        <span class="pb-count">{{ $s['count'] }}</span>
                    </div>
                    @endif
                @endforeach
            @else
                <div class="empty-state"><i class="fas fa-list"></i>No data for this period.</div>
            @endif
        </div>

        {{-- Booking type breakdown table --}}
        <div class="an-card">
            <div class="sec-title"><i class="fas fa-table"></i>Sessions by Type</div>
            @if(array_sum($bookingTypeData) > 0)
                <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--border-soft);">
                            <th style="text-align:left;padding:0.5rem 0.75rem;color:var(--text-secondary);font-weight:600;">Type</th>
                            <th style="text-align:right;padding:0.5rem 0.75rem;color:var(--text-secondary);font-weight:600;">Count</th>
                            <th style="text-align:right;padding:0.5rem 0.75rem;color:var(--text-secondary);font-weight:600;">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $btTotal = array_sum($bookingTypeData); @endphp
                        @foreach($bookingTypeData as $type => $count)
                        <tr style="border-bottom:1px solid var(--border-soft);">
                            <td style="padding:0.55rem 0.75rem;color:var(--text-primary);">{{ $type }}</td>
                            <td style="padding:0.55rem 0.75rem;text-align:right;font-weight:700;color:var(--maroon-soft);">{{ $count }}</td>
                            <td style="padding:0.55rem 0.75rem;text-align:right;color:var(--text-secondary);">{{ round(($count/$btTotal)*100,1) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="border-top:2px solid var(--border-soft);">
                            <td style="padding:0.55rem 0.75rem;font-weight:700;">Total</td>
                            <td style="padding:0.55rem 0.75rem;text-align:right;font-weight:800;color:var(--maroon-soft);">{{ $btTotal }}</td>
                            <td style="padding:0.55rem 0.75rem;text-align:right;color:var(--text-secondary);">100%</td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="empty-state"><i class="fas fa-table"></i>No data for this period.</div>
            @endif
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
    const palette = ['#7a2a2a','#d4af37','#2d7a4f','#2a5a7a','#7c3aed','#e67e22','#17a2b8','#e74c3c','#c0392b'];

    // ── Monthly bar + trend line ─────────────────────────────────────
    @php
        $mLabels = collect($monthlyData)->pluck('month');
        $mCounts = collect($monthlyData)->pluck('count');
    @endphp
    @if(collect($monthlyData)->sum('count') > 0)
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: @json($mLabels),
            datasets: [
                {
                    label: 'Sessions Availed',
                    data: @json($mCounts),
                    backgroundColor: 'rgba(122,42,42,0.15)',
                    borderColor: maroon,
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Trend',
                    data: @json($mCounts),
                    type: 'line',
                    borderColor: gold,
                    backgroundColor: 'transparent',
                    borderWidth: 2.5,
                    pointBackgroundColor: gold,
                    pointRadius: 4,
                    tension: 0.4,
                }
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

    // ── Status doughnut ──────────────────────────────────────────────
    @php
        $sLabels = collect($statusData)->where('count', '>', 0)->pluck('label')->values();
        $sCounts = collect($statusData)->where('count', '>', 0)->pluck('count')->values();
    @endphp
    @if(collect($statusData)->sum('count') > 0)
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($sLabels),
            datasets: [{
                data: @json($sCounts),
                backgroundColor: palette,
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, padding: 14, font: { size: 11 } } },
                tooltip: { callbacks: {
                    label: ctx => {
                        const total = ctx.dataset.data.reduce((a,b) => a+b, 0);
                        return ` ${ctx.label}: ${ctx.parsed} (${((ctx.parsed/total)*100).toFixed(1)}%)`;
                    }
                }}
            },
            cutout: '60%',
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
            plugins: { legend: { position: 'right', labels: { boxWidth: 12, padding: 14, font: { size: 11 } } } }
        }
    });
    @endif

});
</script>
@endpush
