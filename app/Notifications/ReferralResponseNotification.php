<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReferralResponseNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public string $response,    // 'accepted' or 'rejected'
        public string $respondedBy  // 'student' or 'counselor'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $label = $this->response === 'accepted' ? 'Referral Accepted' : 'Referral Rejected';
        $by    = $this->respondedBy === 'student' ? 'the student' : 'the counselor';

        return [
            'title'          => $label,
            'message'        => 'The referral has been ' . $this->response . ' by ' . $by . '. Case #' . $this->appointment->case_number,
            'appointment_id' => $this->appointment->id,
            'case_number'    => $this->appointment->case_number,
            'response'       => $this->response,
            'responded_by'   => $this->respondedBy,
            'type'           => 'referral_response',
        ];
    }
}
