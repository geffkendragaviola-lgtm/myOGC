<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Reschedule Request</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #d97706; padding: 20px; border-radius: 8px 8px 0 0;">
        <h2 style="color: #fff; margin: 0;">Reschedule Request</h2>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        <p>Hello, <strong>{{ $appointment->student->user->first_name }}</strong>,</p>
        <p>Your appointment has been requested to be rescheduled to <strong>{{ \Carbon\Carbon::parse($appointment->proposed_date)->format('F d, Y') }}</strong> at <strong>{{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($appointment->proposed_end_time)->format('h:i A') }}</strong>.</p>

        <p>Please log in to the system to view your appointment details.</p>
        <p style="color: #6b7280; font-size: 13px; margin-top: 24px;">MSU-IIT Guidance Counseling System</p>
    </div>
</body>
</html>
