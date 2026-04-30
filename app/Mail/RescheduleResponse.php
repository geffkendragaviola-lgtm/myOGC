<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RescheduleResponse extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Appointment $appointment,
        public string $response // 'accepted' or 'rejected'
    ) {}

    public function envelope(): Envelope
    {
        $label = $this->response === 'accepted' ? 'Reschedule Accepted' : 'Reschedule Rejected';
        return new Envelope(subject: $label);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.appointments.reschedule-response');
    }
}
