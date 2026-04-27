<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MarkNoShowAppointments extends Command
{
    protected $signature   = 'appointments:mark-no-show';
    protected $description = 'Mark approved appointments as no_show if the end time passed more than 1 hour ago and the student never responded';

    public function handle(): int
    {
        // Statuses where the student hasn't responded / appointment hasn't been resolved
        $pendingStatuses = ['approved', 'rescheduled'];

        $cutoff = Carbon::now()->subHour();

        $appointments = Appointment::whereIn('status', $pendingStatuses)
            ->whereRaw("CONCAT(appointment_date, ' ', end_time) <= ?", [$cutoff->format('Y-m-d H:i:s')])
            ->get();

        $count = 0;
        foreach ($appointments as $appointment) {
            $appointment->update([
                'status' => 'no_show',
                'notes'  => trim(($appointment->notes ?? '') . "\nAuto-marked as no_show: student did not attend. Processed at " . now()->toDateTimeString()),
            ]);
            $count++;
        }

        $this->info("Marked {$count} appointment(s) as no_show.");
        Log::info("appointments:mark-no-show: marked {$count} appointments as no_show.");

        return self::SUCCESS;
    }
}
