<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Appointment Schedule Notice</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #d97706; padding: 20px; border-radius: 8px 8px 0 0;">
        <h2 style="color: #fff; margin: 0;">⚠ Appointment Schedule Notice</h2>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        <p>Hello, <strong>{{ $studentFirstName }}</strong>,</p>
        <p>
            A new event has been scheduled that overlaps with your existing appointment.
            Please review the details below.
        </p>

        <h3 style="margin-top: 20px; margin-bottom: 8px; color: #d97706;">Event Details</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold; width: 35%;">Event</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $event->title }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Date</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $event->date_range }}</td>
            </tr>
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Time</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $event->time_range }}</td>
            </tr>
            @if($event->location)
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Location</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $event->location }}</td>
            </tr>
            @endif
        </table>

        <h3 style="margin-top: 20px; margin-bottom: 8px; color: #dc2626;">Your Conflicting Appointment</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold; width: 35%;">Date</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Time</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                </td>
            </tr>
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Counselor</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $appointment->counselor->user->first_name }} {{ $appointment->counselor->user->last_name }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px;">Please log in to the system to manage your appointment if needed.</p>
        <p style="color: #6b7280; font-size: 13px; margin-top: 24px;">MSU-IIT Guidance Counseling System</p>
    </div>
</body>
</html>
