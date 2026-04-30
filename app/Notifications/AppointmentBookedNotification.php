<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentBookedNotification extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $suffix = $this->appointment->is_appointment_high_risk
            ? ' ⚠ High-risk: ' . $this->appointment->appointment_high_risk_notes
            : '';

        return [
            'title'          => 'New Appointment Booking',
            'message'        => 'A student has booked an appointment.' . $suffix,
            'appointment_id' => $this->appointment->id,
            'type'           => 'appointment_booked',
        ];
    }
}
