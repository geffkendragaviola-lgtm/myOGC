<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\College;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $colleges = College::orderBy('name')->get();

        $collegeId  = $request->input('college_id');
        $year       = $request->input('year', now()->year);
        $dateFrom   = $request->input('date_from');
        $dateTo     = $request->input('date_to');

        // Base query scope
        $base = Appointment::query();

        if ($collegeId) {
            $base->whereHas('student', fn($q) => $q->where('college_id', $collegeId));
        }

        if ($dateFrom && $dateTo) {
            $base->whereBetween('appointment_date', [$dateFrom, $dateTo]);
        } else {
            $base->whereYear('appointment_date', $year);
        }

        // ── Summary cards ──────────────────────────────────────────────
        $totalAppointments = (clone $base)->count();
        $totalStudents     = (clone $base)->distinct('student_id')->count('student_id');
        $completedCount    = (clone $base)->where('status', 'completed')->count();
        $pendingCount      = (clone $base)->whereIn('status', ['pending', 'approved'])->count();

        // ── Status breakdown ───────────────────────────────────────────
        $statusCounts = (clone $base)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $allStatuses = [
            'pending'             => 'Pending',
            'approved'            => 'Approved',
            'completed'           => 'Completed',
            'cancelled'           => 'Cancelled',
            'referred'            => 'Referred',
            'rescheduled'         => 'Rescheduled',
            'reschedule_requested'=> 'Reschedule Requested',
            'reschedule_rejected' => 'Reschedule Rejected',
        ];

        $statusData = [];
        foreach ($allStatuses as $key => $label) {
            $statusData[] = [
                'key'   => $key,
                'label' => $label,
                'count' => $statusCounts[$key] ?? 0,
            ];
        }

        // ── Monthly counseling availed ─────────────────────────────────
        $monthlyRaw = (clone $base)
            ->select(
                DB::raw('EXTRACT(MONTH FROM appointment_date)::int as month'),
                DB::raw('count(*) as total')
            )
            ->whereIn('status', ['completed', 'approved', 'rescheduled'])
            ->groupBy(DB::raw('EXTRACT(MONTH FROM appointment_date)::int'))
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = [
                'month' => Carbon::create()->month($m)->format('M'),
                'count' => $monthlyRaw[$m] ?? 0,
            ];
        }

        // ── Per-college breakdown ──────────────────────────────────────
        $collegeData = College::withCount([
            'students as appointment_count' => function ($q) use ($year, $dateFrom, $dateTo, $collegeId) {
                $q->whereHas('appointments', function ($aq) use ($year, $dateFrom, $dateTo) {
                    if ($dateFrom && $dateTo) {
                        $aq->whereBetween('appointment_date', [$dateFrom, $dateTo]);
                    } else {
                        $aq->whereYear('appointment_date', $year);
                    }
                });
                if ($collegeId) {
                    $q->where('college_id', $collegeId);
                }
            },
        ])->orderBy('name')->get();

        // Per-college appointment counts (actual appointments, not students)
        $collegeAppointmentCounts = Appointment::query()
            ->select('colleges.name as college_name', DB::raw('count(*) as total'))
            ->join('students', 'appointments.student_id', '=', 'students.id')
            ->join('colleges', 'students.college_id', '=', 'colleges.id')
            ->when($collegeId, fn($q) => $q->where('students.college_id', $collegeId))
            ->when($dateFrom && $dateTo,
                fn($q) => $q->whereBetween('appointment_date', [$dateFrom, $dateTo]),
                fn($q) => $q->whereYear('appointment_date', $year)
            )
            ->groupBy('colleges.name')
            ->orderBy('colleges.name')
            ->pluck('total', 'college_name')
            ->toArray();

        // ── Booking type breakdown ─────────────────────────────────────
        $bookingTypeData = (clone $base)
            ->select('booking_type', DB::raw('count(*) as total'))
            ->whereNotNull('booking_type')
            ->groupBy('booking_type')
            ->pluck('total', 'booking_type')
            ->toArray();

        // Available years for filter
        $availableYears = Appointment::selectRaw('EXTRACT(YEAR FROM appointment_date)::int as yr')
            ->groupBy('yr')
            ->orderBy('yr', 'desc')
            ->pluck('yr')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        return view('analytics.index', compact(
            'colleges',
            'collegeId',
            'year',
            'dateFrom',
            'dateTo',
            'totalAppointments',
            'totalStudents',
            'completedCount',
            'pendingCount',
            'statusData',
            'monthlyData',
            'collegeAppointmentCounts',
            'bookingTypeData',
            'availableYears'
        ));
    }

    public function counselor(Request $request)
    {
        $user = auth()->user();

        // Get all counselor assignments for this user
        $counselorAssignments = \App\Models\Counselor::with('college')
            ->where('user_id', $user->id)
            ->get();

        if ($counselorAssignments->isEmpty()) {
            abort(404, 'Counselor profile not found.');
        }

        $counselor      = $counselorAssignments->first();
        $counselorIds   = $counselorAssignments->pluck('id');
        $collegeIds     = $counselorAssignments->pluck('college_id')->unique()->values();
        $colleges       = $counselorAssignments->pluck('college')->filter()->unique('id')->values();
        $collegeName    = $colleges->count() === 1
            ? $colleges->first()->name
            : $colleges->pluck('name')->implode(' / ');

        $year      = $request->input('year', now()->year);
        $dateFrom  = $request->input('date_from');
        $dateTo    = $request->input('date_to');

        // Base: appointments handled by this counselor's college(s)
        $base = Appointment::query()
            ->whereHas('student', fn($q) => $q->whereIn('college_id', $collegeIds));

        if ($dateFrom && $dateTo) {
            $base->whereBetween('appointment_date', [$dateFrom, $dateTo]);
        } else {
            $base->whereYear('appointment_date', $year);
        }

        // ── Summary cards ──────────────────────────────────────────────
        $totalAppointments = (clone $base)->count();
        $totalStudents     = (clone $base)->distinct('student_id')->count('student_id');
        $completedCount    = (clone $base)->where('status', 'completed')->count();
        $pendingCount      = (clone $base)->whereIn('status', ['pending', 'approved'])->count();
        $referralCount     = (clone $base)->where('status', 'referred')->count();
        $cancelledCount    = (clone $base)->where('status', 'cancelled')->count();

        // ── Status breakdown ───────────────────────────────────────────
        $statusCounts = (clone $base)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $allStatuses = [
            'pending'              => 'Pending',
            'approved'             => 'Approved',
            'completed'            => 'Completed',
            'cancelled'            => 'Cancelled',
            'referred'             => 'Referred',
            'rescheduled'          => 'Rescheduled',
            'reschedule_requested' => 'Reschedule Requested',
            'reschedule_rejected'  => 'Reschedule Rejected',
        ];

        $statusData = [];
        foreach ($allStatuses as $key => $label) {
            $statusData[] = [
                'key'   => $key,
                'label' => $label,
                'count' => $statusCounts[$key] ?? 0,
            ];
        }

        // ── Monthly counseling availed ─────────────────────────────────
        $monthlyRaw = (clone $base)
            ->select(
                DB::raw('EXTRACT(MONTH FROM appointment_date)::int as month'),
                DB::raw('count(*) as total')
            )
            ->whereIn('status', ['completed', 'approved', 'rescheduled'])
            ->groupBy(DB::raw('EXTRACT(MONTH FROM appointment_date)::int'))
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = [
                'month' => Carbon::create()->month($m)->format('M'),
                'count' => $monthlyRaw[$m] ?? 0,
            ];
        }

        // ── Booking type breakdown ─────────────────────────────────────
        $bookingTypeData = (clone $base)
            ->select('booking_type', DB::raw('count(*) as total'))
            ->whereNotNull('booking_type')
            ->groupBy('booking_type')
            ->pluck('total', 'booking_type')
            ->toArray();

        // ── Top concerns ───────────────────────────────────────────────
        $topConcerns = (clone $base)
            ->select('booking_type', DB::raw('count(*) as total'))
            ->where('status', 'completed')
            ->whereNotNull('booking_type')
            ->groupBy('booking_type')
            ->orderByDesc('total')
            ->pluck('total', 'booking_type')
            ->toArray();

        // ── Available years ────────────────────────────────────────────
        $availableYears = Appointment::whereHas('student', fn($q) => $q->whereIn('college_id', $collegeIds))
            ->selectRaw('EXTRACT(YEAR FROM appointment_date)::int as yr')
            ->groupBy('yr')
            ->orderBy('yr', 'desc')
            ->pluck('yr')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        return view('analytics.counselor', compact(
            'counselor',
            'colleges',
            'collegeName',
            'year',
            'dateFrom',
            'dateTo',
            'totalAppointments',
            'totalStudents',
            'completedCount',
            'pendingCount',
            'referralCount',
            'cancelledCount',
            'statusData',
            'monthlyData',
            'bookingTypeData',
            'topConcerns',
            'availableYears'
        ));
    }
}
