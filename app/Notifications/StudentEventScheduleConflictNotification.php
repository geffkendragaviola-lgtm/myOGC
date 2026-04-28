<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudentEventScheduleConflictNotification extends Notification
{
    use Queueable;

    public function __construct(public Event $event, public Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $date = \Carbon\Carbon::parse($this->appointment->appointment_date)->format('F j, Y');
        $time = \Carbon\Carbon::parse($this->appointment->start_time)->format('g:i A');

        return [
            'title'          => 'Appointment Schedule Notice',
            'message'        => "The event \"{$this->event->title}\" overlaps with your appointment on {$date} at {$time}. Please check your schedule.",
            'event_id'       => $this->event->id,
            'appointment_id' => $this->appointment->id,
            'type'           => 'student_event_schedule_conflict',
        ];
    }
}
