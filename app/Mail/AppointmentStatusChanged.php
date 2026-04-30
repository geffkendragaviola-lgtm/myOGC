<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Appointment $appointment,
        public string $newStatus
    ) {}

    public function envelope(): Envelope
    {
        $labels = [
            'approved'  => 'Appointment Approved',
            'cancelled' => 'Appointment Cancelled',
            'no_show'   => 'Appointment Marked as No Show',
            'completed' => 'Appointment Completed',
        ];

        $subject = ($labels[$this->newStatus] ?? 'Appointment Update');

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.appointments.status-changed');
    }
}
