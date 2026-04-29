<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Appointment Referral</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #7c3aed; padding: 20px; border-radius: 8px 8px 0 0;">
        <h2 style="color: #fff; margin: 0;">Appointment Referral</h2>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        <p>Hello, <strong>{{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}</strong>,</p>
        <p>Your counselor has referred your appointment to another counselor. Please log in to accept or reject the new schedule.</p>

        <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold; width: 40%;">Case Number</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $appointment->case_number }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Original Counselor</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    {{ $appointment->originalCounselor->user->first_name }} {{ $appointment->originalCounselor->user->last_name }}
                </td>
            </tr>
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Referred To</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    {{ $appointment->referredCounselor->user->first_name }} {{ $appointment->referredCounselor->user->last_name }}
                </td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Proposed Date</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ \Carbon\Carbon::parse($appointment->proposed_date)->format('F d, Y') }}</td>
            </tr>
            <tr style="background: #f9fafb;">
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Proposed Time</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    {{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($appointment->proposed_end_time)->format('h:i A') }}
                </td>
            </tr>
            @if($appointment->referral_reason)
            <tr>
                <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Reason</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $appointment->referral_reason }}</td>
            </tr>
            @endif
        </table>

        <p>Please log in to the system to accept or reject the proposed schedule.</p>
        <p style="color: #6b7280; font-size: 13px; margin-top: 24px;">MSU-IIT Guidance Counseling System</p>
    </div>
</body>
</html>
