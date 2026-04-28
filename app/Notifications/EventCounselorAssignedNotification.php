<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventCounselorAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Event $event,
        public bool $isUpdate = false,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        if ($this->isUpdate) {
            return [
                'title'    => 'Event Updated',
                'message'  => "The event \"{$this->event->title}\" you are assigned to has been updated. New schedule: {$this->event->date_range}.",
                'event_id' => $this->event->id,
                'type'     => 'event_counselor_updated',
            ];
        }

        return [
            'title'    => 'Event Assignment',
            'message'  => "You have been assigned to the event \"{$this->event->title}\" on {$this->event->date_range}.",
            'event_id' => $this->event->id,
            'type'     => 'event_counselor_assigned',
        ];
    }
}
