<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentRescheduledNotification extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $date      = \Carbon\Carbon::parse($this->appointment->proposed_date ?? $this->appointment->appointment_date)->format('F d, Y');
        $time      = \Carbon\Carbon::parse($this->appointment->proposed_start_time ?? $this->appointment->start_time)->format('h:i A');
        $firstName = $this->appointment->student->user->first_name;

        return [
            'title'          => 'Reschedule Request',
            'message'        => "Hi {$firstName}, your counselor has requested to reschedule your appointment to {$date} at {$time}.",
            'appointment_id' => $this->appointment->id,
            'case_number'    => $this->appointment->case_number,
            'type'           => 'appointment_rescheduled',
        ];
    }
}
