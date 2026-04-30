<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentReferredNotification extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $date      = \Carbon\Carbon::parse($this->appointment->appointment_date)->format('F d, Y');
        $time      = \Carbon\Carbon::parse($this->appointment->start_time)->format('h:i A');
        $timeEnd   = \Carbon\Carbon::parse($this->appointment->end_time)->format('h:i A');
        $firstName = $this->appointment->student->user->first_name;

        return [
            'title'          => 'Appointment Referral',
            'message'        => "Hello {$firstName}, your appointment has been referred on {$date} at {$time} – {$timeEnd}. Please log in to the system to view your appointment details.",
            'appointment_id' => $this->appointment->id,
            'type'           => 'appointment_referred',
        ];
    }
}
