<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventScheduleConflictNotification extends Notification
{
    use Queueable;

    public function __construct(public Event $event, public int $conflictCount) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'          => 'Schedule Conflict Detected',
            'message'        => "The event \"{$this->event->title}\" overlaps with {$this->conflictCount} existing schedule(s) on your calendar.",
            'event_id'       => $this->event->id,
            'conflict_count' => $this->conflictCount,
            'type'           => 'event_schedule_conflict',
        ];
    }
}
