<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Referral Response</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    @php
        $accepted = $response === 'accepted';
        $color = $accepted ? '#16a34a' : '#dc2626';
        $label = $accepted ? 'Accepted' : 'Rejected';
    @endphp

    <div style="background: {{ $color }}; padding: 20px; border-radius: 8px 8px 0 0;">
        <h2 style="color: #fff; margin: 0;">Referral {{ $label }}</h2>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        @if($respondedBy === 'student')
            @php
                $recipientName = $appointment->originalCounselor
                    ? $appointment->originalCounselor->user->first_name . ' ' . $appointment->originalCounselor->user->last_name
                    : $appointment->counselor->user->first_name . ' ' . $appointment->counselor->user->last_name;
            @endphp
            <p>Hello, <strong>{{ $recipientName }}</strong>,</p>
            <p>
                The student <strong>{{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}</strong>
                has <strong>{{ strtolower($label) }}</strong> the referral.
            </p>
            <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
                <tr style="background: #f9fafb;">
                    <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold; width: 40%;">Case Number</td>
                    <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $appointment->case_number }}</td>
                </tr>
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
                    <td style="padding: 10px; border: 1px solid #e5e7eb; font-weight: bold;">Response</td>
                    <td style="padding: 10px; border: 1px solid #e5e7eb;">
                        <span style="color: {{ $color }}; font-weight: bold;">{{ $label }}</span>
                    </td>
                </tr>
            </table>
        @else
            {{-- Recipient is the student --}}
            <p>Hello, <strong>{{ $appointment->student->user->first_name }}</strong>,</p>
            <p>The referred counselor has <strong>{{ strtolower($label) }}</strong> your referral.</p>
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
        @endif

        <p>Please log in to the system to view the updated appointment details.</p>
        <p style="color: #6b7280; font-size: 13px; margin-top: 24px;">MSU-IIT Guidance Counseling System</p>
    </div>
</body>
</html>
