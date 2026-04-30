<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentCancelledNotification extends Notification
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
            'title'          => 'Appointment Cancelled',
            'message'        => 'An appointment has been cancelled.',
            'appointment_id' => $this->appointment->id,
            'type'           => 'appointment_cancelled',
        ];
    }
}
