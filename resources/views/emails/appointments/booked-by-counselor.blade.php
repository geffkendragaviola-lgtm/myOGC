<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Appointment Scheduled</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #16a34a; padding: 20px; border-radius: 8px 8px 0 0;">
        <h2 style="color: #fff; margin: 0;">Appointment Scheduled for You</h2>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        <p>Hello, <strong>{{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}</strong>,</p>
        <p>Your appointment has been approved on <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</strong> at <strong>{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</strong>. Please log in to the system to view your appointment details.</p>

        <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Counselor</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    {{ $appointment->counselor->user->first_name }} {{ $appointment->counselor->user->last_name }}
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
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $appointment->booking_type }} ({{ $appointment->booking_category }})</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Concern</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $appointment->concern }}</td>
            </tr>
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Status</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    <span style="color: #16a34a; font-weight: bold;">Approved</span>
                </td>
            </tr>
        </table>

        <p>Please log in to the system to view your appointment details.</p>
        <p style="color: #6b7280; font-size: 13px; margin-top: 24px;">MSU-IIT Guidance Counseling System</p>
    </div>
</body>
</html>
