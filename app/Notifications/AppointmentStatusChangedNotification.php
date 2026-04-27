<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $labels = [
            'approved'  => 'Appointment Approved',
            'cancelled' => 'Appointment Cancelled',
            'no_show'   => 'Appointment Marked as No Show',
            'completed' => 'Appointment Completed',
        ];

        $title = $labels[$this->newStatus] ?? 'Appointment Update';
        $date  = \Carbon\Carbon::parse($this->appointment->appointment_date)->format('F d, Y');
        $time  = \Carbon\Carbon::parse($this->appointment->start_time)->format('h:i A');
        $firstName = $this->appointment->student->user->first_name;

        return [
            'title'          => $title,
            'message'        => "Hi {$firstName}, your appointment on {$date} at {$time} has been " . strtolower($labels[$this->newStatus] ?? $this->newStatus) . '.',
            'appointment_id' => $this->appointment->id,
            'case_number'    => $this->appointment->case_number,
            'status'         => $this->newStatus,
            'type'           => 'appointment_status_changed',
        ];
    }
}
