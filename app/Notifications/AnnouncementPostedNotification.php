<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class AnnouncementPostedNotification extends Notification
{
    use Queueable;

    public function __construct(public Announcement $announcement) {}

    public function via(object $notifiable): array
    {
        if (!empty($notifiable->email)) {
            return ['mail', 'database'];
        }

        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $announcement = $this->announcement;
        $content = strip_tags((string) $announcement->content);
        $start = $announcement->start_date ? \Carbon\Carbon::parse($announcement->start_date)->format('F j, Y g:i A') : null;
        $end = $announcement->end_date ? \Carbon\Carbon::parse($announcement->end_date)->format('F j, Y g:i A') : null;
        $dateTime = $start;
        if ($start && $end) {
            $dateTime .= ' - ' . $end;
        }

        // Determine target audience
        if ($announcement->for_all_colleges) {
            $audience = 'All Colleges';
        } else {
            $colleges = $announcement->colleges->pluck('name')->join(', ');
            $audience = $colleges;
        }
        if (!empty($announcement->year_levels)) {
            $levels = collect($announcement->year_levels)->join(', ');
            $audience .= ' (Year Levels: ' . $levels . ')';
        }

        $mail = (new MailMessage)
            ->subject('Announcement: ' . $announcement->title)
            ->from('no-reply@msu-iit.edu', 'MSU-IIT Guidance')
            ->greeting('Hello ' . ($notifiable->full_name ?? ''))
            ->line('**' . $announcement->title . '**')
            ->line($content);

        if ($dateTime) {
            $mail->line('Date & Time: ' . $dateTime);
        }

        $mail->line('Target Audience: ' . $audience)
            ->line('Please log in to the system to view more details')
            ->salutation("\r\n");

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Announcement',
            'message' => $this->announcement->title,
            'announcement_id' => $this->announcement->id,
            'type' => 'announcement_posted',
        ];
    }
}
