<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Appointment Update</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    @php
        $colors = [
            'approved'  => '#16a34a',
            'cancelled' => '#dc2626',
            'no_show'   => '#d97706',
            'completed' => '#1a56db',
        ];
        $labels = [
            'approved'  => 'Approved',
            'cancelled' => 'Cancelled',
            'no_show'   => 'Marked as No Show',
            'completed' => 'Completed',
        ];
        $color = $colors[$newStatus] ?? '#1a56db';
        $label = $labels[$newStatus] ?? ucfirst($newStatus);
    @endphp

    <div style="background: {{ $color }}; padding: 20px; border-radius: 8px 8px 0 0;">
        <h2 style="color: #fff; margin: 0;">Appointment {{ $label }}</h2>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        <p>Hello, <strong>{{ $appointment->student->user->first_name }}</strong>,</p>
        <p>Your appointment has been <strong>{{ strtolower($label) }}</strong>.</p>

        <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold; width: 40%;">Date</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Time</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                </td>
            </tr>
        </table>

        <p>Please log in to the system to view your appointment details.</p>
        <p style="color: #6b7280; font-size: 13px; margin-top: 24px;">MSU-IIT Guidance Counseling System</p>
    </div>
</body>
</html>
