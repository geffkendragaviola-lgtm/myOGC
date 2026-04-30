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
            'message'        => 'A student has been referred to you by ' .
                                ($this->appointment->originalCounselor?->user
                                    ? $this->appointment->originalCounselor->user->first_name . ' ' . $this->appointment->originalCounselor->user->last_name
                                    : 'another counselor') .
                                '. Awaiting student confirmation.',
            'appointment_id' => $this->appointment->id,
            'type'           => 'appointment_referred_to_counselor',
        ];
    }
}
