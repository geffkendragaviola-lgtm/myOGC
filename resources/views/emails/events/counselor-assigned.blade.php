<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>{{ $isUpdate ? 'Event Updated' : 'Event Assignment' }}</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: {{ $isUpdate ? '#b45309' : '#7a2a2a' }}; padding: 20px; border-radius: 8px 8px 0 0;">
        <h2 style="color: #fff; margin: 0;">
            {{ $isUpdate ? '✏️ Event Updated' : '📅 You\'ve Been Assigned to an Event' }}
        </h2>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        <p>Hello, <strong>{{ $counselor->user->first_name }} {{ $counselor->user->last_name }}</strong>,</p>

        @if($isUpdate)
            <p>An event you are assigned to has been <strong>updated</strong>. Please review the latest details below.</p>
        @else
            <p>You have been assigned as an attending counselor for the following event.</p>
        @endif

        <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold; width: 35%;">Event</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $event->title }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Type</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ ucfirst($event->type) }}</td>
            </tr>
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Date</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $event->date_range }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Time</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $event->time_range }}</td>
            </tr>
            @if($event->location)
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Location</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $event->location }}</td>
            </tr>
            @endif
            @if($event->description)
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Description</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $event->description }}</td>
            </tr>
            @endif
        </table>

        @if($counselor->google_calendar_id)
            <p>Your Google Calendar has been {{ $isUpdate ? 'updated' : 'updated with this event' }} accordingly.</p>
        @endif

        <p>Please log in to the system to view more details.</p>
        <p style="color: #6b7280; font-size: 13px; margin-top: 24px;">MSU-IIT Guidance Counseling System</p>
    </div>
</body>
</html>
