<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Appointment Cancelled</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #dc2626; padding: 20px; border-radius: 8px 8px 0 0;">
        <h2 style="color: #fff; margin: 0;">Appointment Cancelled</h2>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        <p>Hello, <strong>{{ $appointment->counselor->user->first_name }} {{ $appointment->counselor->user->last_name }}</strong>,</p>
        <p>A student has cancelled their appointment.</p>

        <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Student</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                    ({{ $appointment->student->student_id }})
                </td>
            </tr>
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Date</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Time</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                </td>
            </tr>
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Type</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $appointment->booking_type }}</td>
            </tr>
            @if($appointment->cancellation_reason)
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Reason</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $appointment->cancellation_reason }}</td>
            </tr>
            @endif
        </table>

        <p>The time slot is now available for other bookings.</p>
        @if($appointment->is_appointment_high_risk)
        <div style="margin: 16px 0; padding: 12px 16px;  background: #fff5f5; border-radius: 4px;">
            <p style="margin: 0 0 4px; font-weight: bold; color: #991b1b;">High-Risk Appointment</p>
            <p style="margin: 0; font-size: 13px; color: #b91c1c;">{{ $appointment->appointment_high_risk_notes }}</p>
        </div>
        @endif
        <p style="color: #6b7280; font-size: 13px; margin-top: 24px;">MSU-IIT Guidance Counseling System</p>
    </div>
</body>
</html>
