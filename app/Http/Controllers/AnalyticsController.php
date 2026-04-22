<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\College;
use App\Models\Counselor;
use App\Models\Feedback;
use App\Models\SessionNote;
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
        $noShowCount       = (clone $base)->where('status', 'no_show')->count();
        $referralCount     = (clone $base)->where('status', 'referred')->count();
        $completionRate    = $totalAppointments > 0 ? round(($completedCount / $totalAppointments) * 100, 1) : 0;
        $noShowRate        = $totalAppointments > 0 ? round(($noShowCount / $totalAppointments) * 100, 1) : 0;

        // Avg satisfaction (scoped to college if filtered, otherwise global for period)
        $feedbackQuery = Feedback::whereNotNull('satisfaction_rating');
        if ($collegeId) {
            $feedbackQuery->whereHas('user.student', fn($q) => $q->where('college_id', $collegeId));
        }
        $avgSatisfaction = $feedbackQuery->avg('satisfaction_rating');
        $avgSatisfaction = $avgSatisfaction ? round($avgSatisfaction, 1) : null;

        // Follow-up required (from session notes linked to filtered appointments)
        $followUpCount = SessionNote::whereHas('appointment', function ($q) use ($base) {
            $q->whereIn('id', (clone $base)->select('id'));
        })->where('requires_follow_up', true)->count();

        // Counselor utilization: avg appointments per counselor this period
        $counselorApptCounts = (clone $base)
            ->select('counselor_id', DB::raw('count(*) as total'))
            ->whereNotNull('counselor_id')
            ->groupBy('counselor_id')
            ->pluck('total')
            ->toArray();
        $avgCounselorLoad = count($counselorApptCounts) > 0
            ? round(array_sum($counselorApptCounts) / count($counselorApptCounts), 1)
            : 0;

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
            'noShowCount',
            'referralCount',
            'completionRate',
            'noShowRate',
            'avgSatisfaction',
            'followUpCount',
            'avgCounselorLoad',
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

            $counselorAssignments = \App\Models\Counselor::with('college')
                ->where('user_id', $user->id)
                ->get();

            if ($counselorAssignments->isEmpty()) {
                abort(404, 'Counselor profile not found.');
            }

            $counselor         = $counselorAssignments->first();
            $year              = $request->input('year', now()->year);
            $dateFrom          = $request->input('date_from');
            $dateTo            = $request->input('date_to');
            $selectedCollegeId = $request->input('college_id');

            $colleges = $counselorAssignments->pluck('college')->filter()->unique('id')->values();

            // Build per-college analytics
            $collegeAnalytics = [];

            foreach ($counselorAssignments as $assignment) {
                $college = $assignment->college;
                if (!$college) continue;

                if ($selectedCollegeId && $college->id != $selectedCollegeId) continue;

                $base = Appointment::query()
                    ->whereHas('student', fn($q) => $q->where('college_id', $college->id));

                if ($dateFrom && $dateTo) {
                    $base->whereBetween('appointment_date', [$dateFrom, $dateTo]);
                } else {
                    $base->whereYear('appointment_date', $year);
                }

                $statusCounts = (clone $base)
                    ->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray();

                $allStatuses = [
                    'pending'              => 'Pending',
                    'approved'             => 'Approved',
                    'completed'            => 'Completed',
                    'no_show'              => 'No Show',
                    'rejected'             => 'Rejected',
                    'referred'             => 'Referred',
                    'rescheduled'          => 'Rescheduled',
                    'reschedule_requested' => 'Reschedule Requested',
                    'reschedule_rejected'  => 'Reschedule Rejected',
                ];

                $statusData = [];
                foreach ($allStatuses as $key => $label) {
                    $statusData[] = ['key' => $key, 'label' => $label, 'count' => $statusCounts[$key] ?? 0];
                }

                $monthlyRaw = (clone $base)
                    ->select(DB::raw('EXTRACT(MONTH FROM appointment_date)::int as month'), DB::raw('count(*) as total'))
                    ->whereIn('status', ['completed', 'approved', 'rescheduled'])
                    ->groupBy(DB::raw('EXTRACT(MONTH FROM appointment_date)::int'))
                    ->orderBy('month')
                    ->pluck('total', 'month')
                    ->toArray();

                $monthlyData = [];
                for ($m = 1; $m <= 12; $m++) {
                    $monthlyData[] = ['month' => Carbon::create()->month($m)->format('M'), 'count' => $monthlyRaw[$m] ?? 0];
                }

                $bookingTypeData = (clone $base)
                    ->select('booking_type', DB::raw('count(*) as total'))
                    ->whereNotNull('booking_type')
                    ->groupBy('booking_type')
                    ->pluck('total', 'booking_type')
                    ->toArray();

                // Students per college: total enrolled vs booked vs completed
                $totalEnrolled   = \App\Models\Student::where('college_id', $college->id)->count();
                $studentsBooked  = (clone $base)->distinct('student_id')->count('student_id');
                $studentsCompleted = (clone $base)->where('status', 'completed')->distinct('student_id')->count('student_id');

                // Completion rate (completed / total appointments)
                $totalAppts   = (clone $base)->count();
                $completedCnt = (clone $base)->where('status', 'completed')->count();
                $completionRate = $totalAppts > 0 ? round(($completedCnt / $totalAppts) * 100, 1) : 0;

                // No-show rate
                $noShowCnt  = (clone $base)->where('status', 'no_show')->count();
                $noShowRate = $totalAppts > 0 ? round(($noShowCnt / $totalAppts) * 100, 1) : 0;

                // Booking category breakdown
                $bookingCategoryData = (clone $base)
                    ->select('booking_category', DB::raw('count(*) as total'))
                    ->whereNotNull('booking_category')
                    ->groupBy('booking_category')
                    ->pluck('total', 'booking_category')
                    ->toArray();

                // Peak day of week
                $peakDayRaw = (clone $base)
                    ->select(DB::raw("TO_CHAR(appointment_date, 'Day') as day_name"), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw("TO_CHAR(appointment_date, 'Day')"))
                    ->orderByDesc('total')
                    ->first();
                $peakDay = $peakDayRaw ? trim($peakDayRaw->day_name) : null;

                $collegeAnalytics[] = [
                    'college'              => $college,
                    'totalAppointments'    => $totalAppts,
                    'totalStudents'        => $studentsBooked,
                    'completedCount'       => $completedCnt,
                    'pendingCount'         => (clone $base)->whereIn('status', ['pending', 'approved'])->count(),
                    'referralCount'        => (clone $base)->where('status', 'referred')->count(),
                    'cancelledCount'       => $noShowCnt,
                    'completionRate'       => $completionRate,
                    'noShowRate'           => $noShowRate,
                    'totalEnrolled'        => $totalEnrolled,
                    'studentsBooked'       => $studentsBooked,
                    'studentsCompleted'    => $studentsCompleted,
                    'peakDay'              => $peakDay,
                    'statusData'           => $statusData,
                    'monthlyData'          => $monthlyData,
                    'bookingTypeData'      => $bookingTypeData,
                    'bookingCategoryData'  => $bookingCategoryData,
                ];
            }

            $availableYears = Appointment::whereHas('student', fn($q) =>
                    $q->whereIn('college_id', $counselorAssignments->pluck('college_id'))
                )
                ->selectRaw('EXTRACT(YEAR FROM appointment_date)::int as yr')
                ->groupBy('yr')
                ->orderBy('yr', 'desc')
                ->pluck('yr')
                ->toArray();

            if (empty($availableYears)) {
                $availableYears = [now()->year];
            }

            // --- Outside-college analytics ---
            $assignedCollegeIds = $counselorAssignments->pluck('college_id')->filter()->unique()->values();

            $outsideBase = Appointment::with('student.user', 'student.college')
                ->whereIn('counselor_id', $counselorAssignments->pluck('id'))
                ->whereHas('student', fn($q) => $q->whereNotIn('college_id', $assignedCollegeIds));

            if ($dateFrom && $dateTo) {
                $outsideBase->whereBetween('appointment_date', [$dateFrom, $dateTo]);
            } else {
                $outsideBase->whereYear('appointment_date', $year);
            }

            $outsideTotal      = (clone $outsideBase)->count();
            $outsideCompleted  = (clone $outsideBase)->where('status', 'completed')->count();
            $outsidePending    = (clone $outsideBase)->whereIn('status', ['pending', 'approved'])->count();
            $outsideCancelled  = (clone $outsideBase)->whereIn('status', ['cancelled', 'rejected', 'no_show'])->count();

            // Breakdown by college
            $outsideByCollege = (clone $outsideBase)
                ->join('students', 'appointments.student_id', '=', 'students.id')
                ->join('colleges', 'students.college_id', '=', 'colleges.id')
                ->selectRaw('colleges.id as college_id, colleges.name as college_name, count(*) as total, count(distinct appointments.student_id) as unique_students')
                ->groupBy('colleges.id', 'colleges.name')
                ->orderByDesc('total')
                ->get();

            // Recent outside-college appointments
            $outsideRecent = (clone $outsideBase)
                ->with('student.user', 'student.college')
                ->orderByDesc('appointment_date')
                ->limit(10)
                ->get();

            return view('analytics.counselor', compact(
                'counselor',
                'colleges',
                'collegeAnalytics',
                'selectedCollegeId',
                'year',
                'dateFrom',
                'dateTo',
                'availableYears',
                'outsideTotal',
                'outsideCompleted',
                'outsidePending',
                'outsideCancelled',
                'outsideByCollege',
                'outsideRecent'
            ));
        }
}
