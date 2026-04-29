<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReferralResponse extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Appointment $appointment,
        public string $response,      // 'accepted' or 'rejected'
        public string $respondedBy,   // 'student' or 'counselor'
        public ?string $recipientName = null  // override greeting name
    ) {}

    public function envelope(): Envelope
    {
        $label = $this->response === 'accepted' ? 'Referral Accepted' : 'Referral Rejected';
        return new Envelope(subject: $label . ' - ' . $this->appointment->case_number);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.appointments.referral-response');
    }
}
