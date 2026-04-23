<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentBookedByCounselorNotification extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'          => 'Appointment Scheduled for You',
            'message'        => 'Your counselor has scheduled an appointment for you. Case #' . $this->appointment->case_number,
            'appointment_id' => $this->appointment->id,
            'case_number'    => $this->appointment->case_number,
            'type'           => 'appointment_booked_by_counselor',
        ];
    }
}
