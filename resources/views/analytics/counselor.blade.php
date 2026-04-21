@extends('layouts.app')
@section('title', 'Analytics')
@push('styles')
<style>
.an-card{background:#fff;border:1px solid var(--border-soft);border-radius:1rem;box-shadow:0 4px 18px rgba(44,36,32,0.07);padding:1.5rem;}
.stat-card{background:#fff;border:1px solid var(--border-soft);border-radius:1rem;box-shadow:0 4px 18px rgba(44,36,32,0.07);padding:1.2rem 1.4rem;display:flex;align-items:center;gap:1rem;}
.stat-icon{width:2.9rem;height:2.9rem;border-radius:0.75rem;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.si-maroon{background:linear-gradient(135deg,#7a2a2a,#5c1a1a);color:#fff;}
.si-gold{background:linear-gradient(135deg,#d4af37,#c9a227);color:#3a0c0c;}
.si-green{background:linear-gradient(135deg,#2d7a4f,#1e5c38);color:#fff;}
.si-blue{background:linear-gradient(135deg,#2a5a7a,#1a3c5c);color:#fff;}
.si-purple{background:linear-gradient(135deg,#6d28d9,#4c1d95);color:#fff;}
.si-red{background:linear-gradient(135deg,#b91c1c,#991b1b);color:#fff;}
.stat-value{font-size:1.5rem;font-weight:800;color:var(--text-primary);line-height:1;}
.stat-label{font-size:0.78rem;color:var(--text-secondary);margin-top:0.2rem;}
.sec-title{font-size:0.95rem;font-weight:700;color:var(--text-primary);margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;}
.sec-title i{color:var(--maroon-soft);}
.filter-bar{background:#fff;border:1px solid var(--border-soft);border-radius:1rem;padding:1rem 1.25rem;box-shadow:0 2px 10px rgba(44,36,32,0.05);}
.filter-bar select,.filter-bar input[type=date]{border:1px solid var(--border-soft);border-radius:0.5rem;padding:0.45rem 0.75rem;font-size:0.875rem;color:var(--text-primary);background:var(--bg-warm);outline:none;transition:border-color 0.2s;}
.filter-bar select:focus,.filter-bar input[type=date]:focus{border-color:var(--maroon-soft);}
.btn-maroon{background:linear-gradient(135deg,var(--maroon-soft),var(--maroon-medium));color:#fff;border:none;border-radius:0.5rem;padding:0.5rem 1.1rem;font-size:0.875rem;font-weight:600;cursor:pointer;transition:opacity 0.2s;}
.btn-maroon:hover{opacity:0.88;}
.btn-outline{background:transparent;color:var(--maroon-soft);border:1.5px solid var(--maroon-soft);border-radius:0.5rem;padding:0.45rem 1rem;font-size:0.875rem;font-weight:600;cursor:pointer;transition:all 0.2s;text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;}
.btn-outline:hover{background:var(--maroon-soft);color:#fff;}
.college-tabs{display:flex;flex-wrap:wrap;gap:0.5rem;border-bottom:2px solid var(--border-soft);padding-bottom:0;margin-bottom:0;}
.college-tab-btn{position:relative;display:inline-flex;align-items:center;gap:0.5rem;padding:0.65rem 1.25rem;font-size:0.875rem;font-weight:600;color:var(--text-secondary);background:transparent;border:none;border-bottom:3px solid transparent;margin-bottom:-2px;cursor:pointer;border-radius:0.5rem 0.5rem 0 0;transition:all 0.2s ease;white-space:nowrap;}
.college-tab-btn:hover{color:var(--maroon-soft);background:rgba(122,42,42,0.04);}
.college-tab-btn.active{color:var(--maroon-soft);border-bottom-color:var(--maroon-soft);background:rgba(122,42,42,0.05);}
.college-tab-btn .tab-dot{width:0.5rem;height:0.5rem;border-radius:999px;background:var(--gold-primary);flex-shrink:0;}
.college-tab-pane{display:none;}
.college-tab-pane.active{display:block;}
.empty-state{text-align:center;padding:2.5rem 1rem;color:var(--text-muted);font-size:0.9rem;}
.empty-state i{font-size:2.2rem;margin-bottom:0.6rem;opacity:0.3;display:block;}
.reach-card{background:linear-gradient(135deg,rgba(122,42,42,0.04),rgba(212,175,55,0.06));border:1px solid var(--border-soft);border-radius:1rem;padding:1.25rem 1.5rem;}
.reach-ratio{font-size:1.6rem;font-weight:800;color:var(--maroon-soft);line-height:1;}
.reach-sub{font-size:0.75rem;color:var(--text-secondary);margin-top:0.2rem;}
.reach-bar-track{height:0.6rem;background:var(--border-soft);border-radius:999px;overflow:hidden;margin-top:0.6rem;}
.reach-bar-fill{height:100%;border-radius:999px;}
.rate-pill{display:inline-flex;align-items:center;gap:0.35rem;padding:0.25rem 0.65rem;border-radius:999px;font-size:0.75rem;font-weight:700;}
.rate-good{background:rgba(45,122,79,0.12);color:#1e5c38;}
.rate-warn{background:rgba(234,88,12,0.12);color:#c2410c;}
.rate-bad{background:rgba(185,28,28,0.12);color:#991b1b;}
@media print{
    .no-print{display:none!important;}
    .print-only{display:block!important;}
    body{background:#fff!important;font-size:11pt;}
    .ogc-navbar,#ogcSidebar{display:none!important;}
    #ogcMainContent{margin-left:0!important;padding-top:0!important;}
    
    /* Professional print header */
    .print-header{
        display:block!important;
        border-bottom:3px solid #7a2a2a;
        padding-bottom:1rem;
        margin-bottom:1.5rem;
    }
    .print-header h1{
        font-size:1.5rem;
        font-weight:800;
        color:#3a0c0c;
        margin-bottom:0.3rem;
        letter-spacing:-0.02em;
    }
    .print-header p{
        font-size:0.85rem;
        color:#6b5e57;
        margin:0.15rem 0;
    }
    .print-header .report-meta{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-top:0.5rem;
        padding-top:0.5rem;
        border-top:1px solid #e5e0db;
    }
    
    /* Cards and containers */
    .an-card,.stat-card,.reach-card{
        box-shadow:none!important;
        border:1px solid #ddd!important;
        break-inside:avoid;
        page-break-inside:avoid;
        margin-bottom:1rem;
    }
    .stat-card{
        padding:0.8rem 1rem;
    }
    .reach-card{
        padding:1rem;
    }
    
    /* Typography */
    .stat-value{font-size:1.3rem;}
    .stat-label{font-size:0.7rem;}
    .reach-ratio{font-size:1.4rem;}
    .reach-sub{font-size:0.7rem;}
    .sec-title{
        font-size:0.9rem;
        margin-bottom:0.75rem;
        padding-bottom:0.3rem;
        border-bottom:2px solid #7a2a2a;
    }
    
    /* Charts */
    canvas{
        max-width:100%!important;
        height:auto!important;
        page-break-inside:avoid;
    }
    
    /* College tabs - show all */
    .college-tabs{display:none!important;}
    .college-tab-pane{
        display:block!important;
        page-break-before:always;
    }
    .college-tab-pane:first-of-type{
        page-break-before:avoid;
    }
    
    /* College header for each section */
    .college-tab-pane::before{
        content:'';
        display:block;
        height:2px;
        background:linear-gradient(90deg,#7a2a2a,#d4af37);
        margin-bottom:1rem;
    }
    
    /* Grid adjustments */
    .grid{
        display:grid!important;
    }
    .grid-cols-2{grid-template-columns:repeat(2,1fr)!important;}
    .grid-cols-3{grid-template-columns:repeat(3,1fr)!important;}
    
    /* Tables */
    table{
        font-size:0.8rem;
        page-break-inside:avoid;
    }
    table th{
        background:#f5f5f5!important;
        font-weight:700;
    }
    
    /* Rate pills */
    .rate-pill{
        font-size:0.65rem;
        padding:0.2rem 0.5rem;
        border:1px solid currentColor;
    }
    
    /* Page breaks */
    .mb-5{margin-bottom:1.5rem!important;}
    
    /* Footer on each page */
    @page{
        margin:1.5cm 1.5cm 2cm 1.5cm;
        @bottom-center{
            content:"Page " counter(page) " of " counter(pages);
            font-size:9pt;
            color:#6b5e57;
        }
        @bottom-right{
            content:"MSU-IIT OGC Analytics";
            font-size:9pt;
            color:#6b5e57;
        }
    }
}
.print-header{display:none;}
</style>
@endpush
@section('content')
<div class="p-6 space-y-6">

    <div class="print-header mb-4">
        <h1>MSU-IIT Office of Guidance and Counseling</h1>
        <p style="font-weight:600;color:#7a2a2a;font-size:0.95rem;">Counselor Analytics Report</p>
        <div class="report-meta">
            <div>
                <p><strong>Period:</strong> {{ $dateFrom && $dateTo ? $dateFrom.' to '.$dateTo : 'Year '.$year }}</p>
                <p><strong>Prepared by:</strong> {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
            </div>
            <div style="text-align:right;">
                <p><strong>Generated:</strong> {{ now()->format('F j, Y') }}</p>
                <p><strong>Time:</strong> {{ now()->format('g:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-start justify-between gap-3 no-print">
        <div>
            <h1 class="text-xl font-bold" style="color:var(--text-primary)">
                <i class="fas fa-chart-column mr-2" style="color:var(--maroon-soft)"></i>Analytics
            </h1>
            <p class="text-sm" style="color:var(--text-secondary)">Per-college counseling insights</p>
        </div>
        <button onclick="window.print()" class="btn-outline no-print"><i class="fas fa-print"></i> Print Report</button>
    </div>

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
            <button type="submit" class="btn-maroon"><i class="fas fa-magnifying-glass mr-1"></i>Apply</button>
            <a href="{{ route('counselor.analytics') }}" class="btn-outline"><i class="fas fa-rotate-left"></i>Reset</a>
        </form>
    </div>

    @if(empty($collegeAnalytics))
        <div class="an-card"><div class="empty-state"><i class="fas fa-chart-column"></i>No data found for the selected filters.</div></div>
    @else

    <div class="an-card" style="padding:0;overflow:hidden;">
        <div class="college-tabs px-4 pt-4 no-print">
            @foreach($collegeAnalytics as $idx => $ca)
            <button class="college-tab-btn {{ $idx === 0 ? 'active' : '' }}"
                    data-tab="tab_{{ $ca['college']->id }}"
                    onclick="switchTab(this, 'tab_{{ $ca['college']->id }}')">
                <span class="tab-dot"></span>
                {{ $ca['college']->name }}
                @if($ca['college']->code)
                    <span class="text-xs font-normal opacity-60">({{ $ca['college']->code }})</span>
                @endif
            </button>
            @endforeach
        </div>

        <div class="p-5 space-y-5">
        @foreach($collegeAnalytics as $idx => $ca)
        @php
            $cid            = 'college_'.$ca['college']->id;
            $completionRate = $ca['completionRate'];
            $noShowRate     = $ca['noShowRate'];
            $rateClass      = $completionRate >= 70 ? 'rate-good' : ($completionRate >= 40 ? 'rate-warn' : 'rate-bad');
            $nsClass        = $noShowRate <= 10 ? 'rate-good' : ($noShowRate <= 25 ? 'rate-warn' : 'rate-bad');
            $bookedPct      = $ca['totalEnrolled'] > 0 ? round(($ca['studentsBooked']  / $ca['totalEnrolled']) * 100, 1) : 0;
            $completedPct   = $ca['totalEnrolled'] > 0 ? round(($ca['studentsCompleted'] / $ca['totalEnrolled']) * 100, 1) : 0;
        @endphp
        <div class="college-tab-pane {{ $idx === 0 ? 'active' : '' }}" id="tab_{{ $ca['college']->id }}">

            {{-- College header for print --}}
            <div class="print-only" style="display:none;">
                <h2 style="font-size:1.2rem;font-weight:700;color:#7a2a2a;margin-bottom:1rem;">
                    {{ $ca['college']->name }}
                    @if($ca['college']->code)
                        <span style="font-size:0.9rem;font-weight:500;color:#6b5e57;">({{ $ca['college']->code }})</span>
                    @endif
                </h2>
            </div>

            {{-- Summary stat cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 mb-5">
                <div class="stat-card"><div class="stat-icon si-maroon"><i class="fas fa-calendar-check"></i></div><div><div class="stat-value">{{ number_format($ca['totalAppointments']) }}</div><div class="stat-label">Total Appts</div></div></div>
                <div class="stat-card"><div class="stat-icon si-gold"><i class="fas fa-user-graduate"></i></div><div><div class="stat-value">{{ number_format($ca['totalStudents']) }}</div><div class="stat-label">Students Seen</div></div></div>
                <div class="stat-card"><div class="stat-icon si-green"><i class="fas fa-circle-check"></i></div><div><div class="stat-value">{{ number_format($ca['completedCount']) }}</div><div class="stat-label">Completed</div></div></div>
                <div class="stat-card"><div class="stat-icon si-blue"><i class="fas fa-hourglass-half"></i></div><div><div class="stat-value">{{ number_format($ca['pendingCount']) }}</div><div class="stat-label">Pending</div></div></div>
                <div class="stat-card"><div class="stat-icon si-purple"><i class="fas fa-arrow-right-arrow-left"></i></div><div><div class="stat-value">{{ number_format($ca['referralCount']) }}</div><div class="stat-label">Referrals</div></div></div>
                <div class="stat-card"><div class="stat-icon si-red"><i class="fas fa-circle-xmark"></i></div><div><div class="stat-value">{{ number_format($ca['cancelledCount']) }}</div><div class="stat-label">No Show</div></div></div>
            </div>

            {{-- Student Reach cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                <div class="reach-card">
                    <div class="text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-secondary)">
                        <i class="fas fa-users mr-1"></i>Students Booked / Enrolled
                    </div>
                    <div class="reach-ratio">
                        {{ number_format($ca['studentsBooked']) }}<span style="font-size:1rem;font-weight:500;color:var(--text-secondary)"> / {{ number_format($ca['totalEnrolled']) }}</span>
                    </div>
                    <div class="reach-sub">{{ $bookedPct }}% of enrolled students have booked at least once</div>
                    <div class="reach-bar-track">
                        <div class="reach-bar-fill" style="width:{{ min($bookedPct,100) }}%;background:linear-gradient(90deg,#7a2a2a,#d4af37)"></div>
                    </div>
                </div>
                <div class="reach-card">
                    <div class="text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-secondary)">
                        <i class="fas fa-circle-check mr-1"></i>Students Completed / Enrolled
                    </div>
                    <div class="reach-ratio">
                        {{ number_format($ca['studentsCompleted']) }}<span style="font-size:1rem;font-weight:500;color:var(--text-secondary)"> / {{ number_format($ca['totalEnrolled']) }}</span>
                    </div>
                    <div class="reach-sub">{{ $completedPct }}% of enrolled students completed a session</div>
                    <div class="reach-bar-track">
                        <div class="reach-bar-fill" style="width:{{ min($completedPct,100) }}%;background:linear-gradient(90deg,#2d7a4f,#17a2b8)"></div>
                    </div>
                </div>
                <div class="reach-card">
                    <div class="text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-secondary)">
                        <i class="fas fa-gauge-high mr-1"></i>Session Quality
                    </div>
                    <div class="flex gap-4 mt-1 flex-wrap">
                        <div>
                            <div class="reach-ratio" style="font-size:1.2rem;">{{ $completionRate }}%</div>
                            <div class="reach-sub">Completion rate</div>
                            <span class="rate-pill {{ $rateClass }} mt-1">
                                <i class="fas fa-{{ $completionRate >= 70 ? 'arrow-trend-up' : ($completionRate >= 40 ? 'minus' : 'arrow-trend-down') }}"></i>
                                {{ $completionRate >= 70 ? 'Good' : ($completionRate >= 40 ? 'Fair' : 'Low') }}
                            </span>
                        </div>
                        <div>
                            <div class="reach-ratio" style="font-size:1.2rem;">{{ $noShowRate }}%</div>
                            <div class="reach-sub">No-show rate</div>
                            <span class="rate-pill {{ $nsClass }} mt-1">
                                <i class="fas fa-{{ $noShowRate <= 10 ? 'check' : ($noShowRate <= 25 ? 'triangle-exclamation' : 'xmark') }}"></i>
                                {{ $noShowRate <= 10 ? 'Low' : ($noShowRate <= 25 ? 'Moderate' : 'High') }}
                            </span>
                        </div>
                        @if($ca['peakDay'])
                        <div>
                            <div class="reach-ratio" style="font-size:1.1rem;">{{ $ca['peakDay'] }}</div>
                            <div class="reach-sub">Busiest day</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Monthly chart --}}
            <div class="an-card mb-5">
                <div class="sec-title"><i class="fas fa-chart-line"></i>Monthly Sessions — {{ $year }}</div>
                @if(collect($ca['monthlyData'])->sum('count') > 0)
                    <div style="position:relative;height:240px"><canvas id="monthly_{{ $cid }}"></canvas></div>
                @else
                    <div class="empty-state"><i class="fas fa-chart-line"></i>No sessions found for {{ $year }}.</div>
                @endif
            </div>

            {{-- Status breakdown --}}
            <div class="an-card mb-5">
                <div class="sec-title"><i class="fas fa-tag"></i>Status Breakdown</div>
                @if(collect($ca['statusData'])->sum('count') > 0)
                    <div style="position:relative;height:260px"><canvas id="status_{{ $cid }}"></canvas></div>
                @else
                    <div class="empty-state"><i class="fas fa-chart-pie"></i>No data.</div>
                @endif
            </div>

            {{-- Booking type + category --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                @if(array_sum($ca['bookingTypeData']) > 0)
                <div class="an-card">
                    <div class="sec-title"><i class="fas fa-bookmark"></i>Sessions by Booking Type</div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-center">
                        <div style="position:relative;height:200px"><canvas id="booking_{{ $cid }}"></canvas></div>
                        <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                            <thead><tr style="border-bottom:2px solid var(--border-soft);">
                                <th style="text-align:left;padding:0.4rem 0.6rem;color:var(--text-secondary);font-weight:600;">Type</th>
                                <th style="text-align:right;padding:0.4rem 0.6rem;color:var(--text-secondary);font-weight:600;">Count</th>
                                <th style="text-align:right;padding:0.4rem 0.6rem;color:var(--text-secondary);font-weight:600;">%</th>
                            </tr></thead>
                            <tbody>
                                @php $btTotal = array_sum($ca['bookingTypeData']); @endphp
                                @foreach($ca['bookingTypeData'] as $type => $count)
                                <tr style="border-bottom:1px solid var(--border-soft);">
                                    <td style="padding:0.45rem 0.6rem;">{{ $type }}</td>
                                    <td style="padding:0.45rem 0.6rem;text-align:right;font-weight:700;color:var(--maroon-soft);">{{ $count }}</td>
                                    <td style="padding:0.45rem 0.6rem;text-align:right;color:var(--text-secondary);">{{ round(($count/$btTotal)*100,1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot><tr style="border-top:2px solid var(--border-soft);">
                                <td style="padding:0.45rem 0.6rem;font-weight:700;">Total</td>
                                <td style="padding:0.45rem 0.6rem;text-align:right;font-weight:800;color:var(--maroon-soft);">{{ $btTotal }}</td>
                                <td style="padding:0.45rem 0.6rem;text-align:right;color:var(--text-secondary);">100%</td>
                            </tr></tfoot>
                        </table>
                    </div>
                </div>
                @endif

                @if(!empty($ca['bookingCategoryData']) && array_sum($ca['bookingCategoryData']) > 0)
                <div class="an-card">
                    <div class="sec-title"><i class="fas fa-layer-group"></i>How Appointments Were Made</div>
                    <div style="position:relative;height:200px"><canvas id="category_{{ $cid }}"></canvas></div>
                </div>
                @endif
            </div>

        </div>{{-- end tab pane --}}
        @endforeach
        </div>
    </div>

    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
    Chart.defaults.color = '#6b5e57';

    const maroon  = '#7a2a2a';
    const gold    = '#d4af37';
    const palette = ['#7a2a2a','#d4af37','#2d7a4f','#2a5a7a','#7c3aed','#e67e22','#17a2b8','#e74c3c','#c0392b'];

    const initialised = new Set();

    @php
        $jsCollegeData = array_values(array_map(function($ca) {
            return [
                'id'           => $ca['college']->id,
                'monthlyData'  => $ca['monthlyData'],
                'statusData'   => array_values(array_filter($ca['statusData'], fn($s) => $s['count'] > 0)),
                'bookingData'  => $ca['bookingTypeData'],
                'categoryData' => $ca['bookingCategoryData'],
            ];
        }, $collegeAnalytics));
    @endphp
    const collegeData = @json($jsCollegeData);

    function buildCharts(collegeId) {
        const cid  = 'college_' + collegeId;
        const data = collegeData.find(d => d.id == collegeId);
        if (!data) return;

        // Monthly bar + trend line
        const monthlyEl = document.getElementById('monthly_' + cid);
        if (monthlyEl && data.monthlyData.some(m => m.count > 0)) {
            new Chart(monthlyEl, {
                type: 'bar',
                data: {
                    labels: data.monthlyData.map(m => m.month),
                    datasets: [
                        { label: 'Sessions', data: data.monthlyData.map(m => m.count), backgroundColor: 'rgba(122,42,42,0.15)', borderColor: maroon, borderWidth: 2, borderRadius: 6, borderSkipped: false },
                        { label: 'Trend', data: data.monthlyData.map(m => m.count), type: 'line', borderColor: gold, backgroundColor: 'transparent', borderWidth: 2.5, pointBackgroundColor: gold, pointRadius: 4, tension: 0.4 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { boxWidth: 12, padding: 14 } } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Status doughnut
        const statusEl = document.getElementById('status_' + cid);
        if (statusEl && data.statusData.length > 0) {
            new Chart(statusEl, {
                type: 'doughnut',
                data: {
                    labels: data.statusData.map(s => s.label),
                    datasets: [{ data: data.statusData.map(s => s.count), backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } },
                        tooltip: { callbacks: { label: ctx => {
                            const t = ctx.dataset.data.reduce((a,b) => a+b, 0);
                            return ` ${ctx.label}: ${ctx.parsed} (${((ctx.parsed/t)*100).toFixed(1)}%)`;
                        }}}
                    },
                    cutout: '60%'
                }
            });
        }

        // Booking type pie
        const bookingEl = document.getElementById('booking_' + cid);
        const btKeys = Object.keys(data.bookingData);
        const btVals = Object.values(data.bookingData);
        if (bookingEl && btVals.some(v => v > 0)) {
            new Chart(bookingEl, {
                type: 'pie',
                data: {
                    labels: btKeys,
                    datasets: [{ data: btVals, backgroundColor: [maroon, gold, '#2d7a4f', '#2a5a7a', '#7c3aed'], borderWidth: 2, borderColor: '#fff' }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'right', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } } }
                }
            });
        }

        // Booking category horizontal bar
        const categoryEl = document.getElementById('category_' + cid);
        const catKeys = Object.keys(data.categoryData);
        const catVals = Object.values(data.categoryData);
        const catLabels = { online: 'Online', 'walk-in': 'Walk-in', referred: 'Referred', 'called-in': 'Called-in' };
        if (categoryEl && catVals.some(v => v > 0)) {
            new Chart(categoryEl, {
                type: 'bar',
                data: {
                    labels: catKeys.map(k => catLabels[k] || k),
                    datasets: [{ label: 'Appointments', data: catVals, backgroundColor: [maroon, gold, '#2d7a4f', '#2a5a7a'], borderRadius: 6 }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }
    }

    window.switchTab = function (btn, tabId) {
        document.querySelectorAll('.college-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.college-tab-pane').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        const pane = document.getElementById(tabId);
        if (pane) pane.classList.add('active');
        const collegeId = parseInt(tabId.replace('tab_', ''), 10);
        if (!initialised.has(collegeId)) {
            initialised.add(collegeId);
            buildCharts(collegeId);
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        if (collegeData.length > 0) {
            const first = collegeData[0];
            initialised.add(first.id);
            buildCharts(first.id);
        }
    });
})();
</script>
@endpush
