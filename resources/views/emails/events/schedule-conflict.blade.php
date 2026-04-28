<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Schedule Conflict Detected</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #dc2626; padding: 20px; border-radius: 8px 8px 0 0;">
        <h2 style="color: #fff; margin: 0;">⚠ Schedule Conflict Detected</h2>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        <p>Hello, <strong>{{ $counselor->user->first_name }} {{ $counselor->user->last_name }}</strong>,</p>
        <p>
            The event <strong>{{ $event->title }}</strong> has been added to your calendar, but it overlaps with
            existing schedule(s). Please review and take action as needed.
        </p>

        <h3 style="margin-top: 24px; margin-bottom: 8px; color: #dc2626;">Event Details</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold; width: 40%;">Event</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $event->title }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Date(s)</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    {{ \Carbon\Carbon::parse($event->event_start_date)->format('F d, Y') }}
                    @if($event->event_start_date != $event->event_end_date)
                        – {{ \Carbon\Carbon::parse($event->event_end_date)->format('F d, Y') }}
                    @endif
                </td>
            </tr>
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Time</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Location</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $event->location ?? 'N/A' }}</td>
            </tr>
        </table>

        {{-- Appointment conflicts --}}
        @if($conflictingAppointments->isNotEmpty())
        <h3 style="margin-top: 24px; margin-bottom: 8px; color: #dc2626;">Conflicting Appointments</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background: #fee2e2;">
                    <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: left;">Case #</th>
                    <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: left;">Student</th>
                    <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: left;">Date</th>
                    <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: left;">Time</th>
                    <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: left;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conflictingAppointments as $appointment)
                <tr style="{{ $loop->even ? 'background: #f9fafb;' : '' }}">
                    <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $appointment->case_number }}</td>
                    <td style="padding: 10px; border: 1px solid #e5e7eb;">
                        {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                    </td>
                    <td style="padding: 10px; border: 1px solid #e5e7eb;">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                    </td>
                    <td style="padding: 10px; border: 1px solid #e5e7eb;">
                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                    </td>
                    <td style="padding: 10px; border: 1px solid #e5e7eb; text-transform: capitalize;">{{ $appointment->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- Google Calendar conflicts --}}
        @if($calendarConflicts->isNotEmpty())
        <h3 style="margin-top: 24px; margin-bottom: 8px; color: #b45309;">Conflicting Google Calendar Events</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background: #fef3c7;">
                    <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: left;">Event Title</th>
                    <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: left;">Date</th>
                    <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: left;">Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($calendarConflicts as $conflict)
                <tr style="{{ $loop->even ? 'background: #f9fafb;' : '' }}">
                    <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $conflict['title'] }}</td>
                    <td style="padding: 10px; border: 1px solid #e5e7eb;">
                        {{ \Carbon\Carbon::parse($conflict['date'])->format('M d, Y') }}
                    </td>
                    <td style="padding: 10px; border: 1px solid #e5e7eb;">
                        {{ $conflict['start']->format('h:i A') }} – {{ $conflict['end']->format('h:i A') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <p style="margin-top: 20px;">Please log in to the system to reschedule or manage the affected appointments.</p>
        <p style="color: #6b7280; font-size: 13px; margin-top: 24px;">MSU-IIT Guidance Counseling System</p>
    </div>
</body>
</html>
