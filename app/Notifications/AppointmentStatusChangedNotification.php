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
        $timeEnd = \Carbon\Carbon::parse($this->appointment->end_time)->format('h:i A');
        $firstName = $this->appointment->student->user->first_name;

        $statusVerb = match ($this->newStatus) {
            'approved'  => 'approved',
            'cancelled' => 'cancelled',
            'no_show'   => 'marked as no show',
            'completed' => 'completed',
            default     => str_replace('_', ' ', $this->newStatus),
        };

        return [
            'title'          => $title,
            'message'        => "Hello {$firstName}, your appointment has been {$statusVerb} on {$date} at {$time} – {$timeEnd}. Please log in to the system to view your appointment details.",
            'appointment_id' => $this->appointment->id,
            'status'         => $this->newStatus,
            'type'           => 'appointment_status_changed',
        ];
    }
}
