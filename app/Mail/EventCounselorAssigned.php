<?php

namespace App\Mail;

use App\Models\Counselor;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventCounselorAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Event $event,
        public Counselor $counselor,
        public bool $isUpdate = false,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->isUpdate
            ? 'Event Updated – ' . $this->event->title
            : 'Event Assignment – ' . $this->event->title;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.events.counselor-assigned');
    }
}
