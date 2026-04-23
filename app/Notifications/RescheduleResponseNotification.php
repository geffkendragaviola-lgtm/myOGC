<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RescheduleResponseNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public string $response // 'accepted' or 'rejected'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $label = $this->response === 'accepted' ? 'Reschedule Accepted' : 'Reschedule Rejected';

        return [
            'title'          => $label,
            'message'        => 'The student has ' . $this->response . ' your reschedule request. Case #' . $this->appointment->case_number,
            'appointment_id' => $this->appointment->id,
            'case_number'    => $this->appointment->case_number,
            'response'       => $this->response,
            'type'           => 'reschedule_response',
        ];
    }
}
