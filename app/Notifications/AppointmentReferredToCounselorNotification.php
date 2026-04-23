<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentReferredToCounselorNotification extends Notification
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
            'title'          => 'New Referral Assignment',
            'message'        => 'A student has been referred to you. Case #' . $this->appointment->case_number,
            'appointment_id' => $this->appointment->id,
            'case_number'    => $this->appointment->case_number,
            'type'           => 'appointment_referred_to_counselor',
        ];
    }
}
