<?php

namespace App\Mail;

use App\Models\Counselor;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventScheduleConflict extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  \App\Models\Event      $event
     * @param  \App\Models\Counselor  $counselor
     * @param  \App\Models\Appointment[]|\Illuminate\Support\Collection  $conflictingAppointments
     */
    public function __construct(
        public Event $event,
        public Counselor $counselor,
        public $conflictingAppointments
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Schedule Conflict Detected – ' . $this->event->title);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.events.schedule-conflict');
    }
}
