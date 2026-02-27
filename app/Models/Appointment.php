<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;

class Appointment extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (self $appointment) {
            if ($appointment->case_number) {
                return;
            }

            $year = $appointment->appointment_date
                ? $appointment->appointment_date->format('Y')
                : now()->format('Y');

            $appointment->case_number = 'CASE-' . $year . '-' . str_pad((string) $appointment->id, 6, '0', STR_PAD_LEFT);
            $appointment->saveQuietly();
        });
    }

    protected $fillable = [
        'case_number',
        'student_id',
        'counselor_id',
        'appointment_date',
        'start_time',
        'end_time',
        'booking_type',
        'concern',
        'status',
        'notes',
        'session_note_id',
        'referred_to_counselor_id',
        'referral_reason',
        'referral_previous_status',
        'referral_requested_at',
        'referral_outcome',
        'referral_resolved_at',
        'referral_resolved_by_counselor_id',
        'original_counselor_id',
        'google_calendar_event_id',
        'proposed_date',
        'proposed_start_time',
        'proposed_end_time',
        'reschedule_reason',
        'reschedule_requested_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'proposed_date' => 'date',
        'reschedule_requested_at' => 'datetime',
        'referral_requested_at' => 'datetime',
        'referral_resolved_at' => 'datetime',
    ];

    // Add this method to get all valid statuses
    public static function getStatuses()
    {
        return [
            'pending',
            'approved',
            'rejected',
            'cancelled',
            'completed',
            'referred',
            'rescheduled',
            'reschedule_requested',
            'reschedule_rejected'
        ];
    }

    // Relationship to session notes (an appointment can have multiple session notes)
    public function sessionNotes(): HasMany
    {
        return $this->hasMany(SessionNote::class);
    }

    // Relationship to the most recent session note
    public function latestSessionNote(): HasOne
    {
        return $this->hasOne(SessionNote::class)->latest();
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class);
    }

    // Relationship to referred counselor
    public function referredCounselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class, 'referred_to_counselor_id');
    }

    // Relationship to original counselor
    public function originalCounselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class, 'original_counselor_id');
    }

    public function referralResolvedByCounselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class, 'referral_resolved_by_counselor_id');
    }

    public function getReferralBadgeForCounselor(int $counselorId): ?string
    {
        if (!$this->is_referred) {
            return null;
        }

        $suffix = $this->referral_outcome === 'rejected' ? ' (Rejected)' : '';

        if ((int) $this->original_counselor_id === (int) $counselorId) {
            $referredCounselorName = $this->referredCounselor && $this->referredCounselor->user
                ? $this->referredCounselor->user->first_name . ' ' . $this->referredCounselor->user->last_name
                : 'Unknown Counselor';

            return "Referred to {$referredCounselorName}{$suffix}";
        }

        if ((int) $this->referred_to_counselor_id === (int) $counselorId) {
            $originalCounselorName = $this->originalCounselor && $this->originalCounselor->user
                ? $this->originalCounselor->user->first_name . ' ' . $this->originalCounselor->user->last_name
                : 'Unknown Counselor';

            return "Referred from {$originalCounselorName}{$suffix}";
        }

        return null;
    }

    // Helper method to check if appointment has session notes
    public function getHasSessionNotesAttribute(): bool
    {
        return $this->sessionNotes()->exists();
    }

    // Helper method to get session notes count
    public function getSessionNotesCountAttribute(): int
    {
        return $this->sessionNotes()->count();
    }

    // Helper method to check if appointment was referred
    public function getIsReferredAttribute(): bool
    {
        return !is_null($this->referred_to_counselor_id);
    }

    // Helper method to check if appointment was referred to current counselor
    public function isReferredToMe($counselorId): bool
    {
        return $this->is_referred && (int) $this->referred_to_counselor_id === (int) $counselorId;
    }

    // Helper method to check if appointment was referred from another counselor
    public function isReferredFromAnother($counselorId): bool
    {
        return $this->is_referred && (int) $this->original_counselor_id !== (int) $counselorId && (int) $this->counselor_id === (int) $counselorId;
    }

    // Helper method to get referral display text for referring counselor
    public function getReferralDisplayForReferringCounselor(): string
    {
        if ($this->is_referred) {
            $referredCounselorName = $this->referredCounselor ?
                $this->referredCounselor->user->first_name . ' ' . $this->referredCounselor->user->last_name :
                'Unknown Counselor';

            $suffix = $this->referral_outcome === 'rejected' ? ' (Rejected)' : '';
            return "Referred to {$referredCounselorName}{$suffix}";
        }
        return ucfirst($this->status);
    }

    // Helper method to get referral display text for receiving counselor
    public function getReferralDisplayForReceivingCounselor(): string
    {
        if ($this->is_referred && $this->original_counselor_id) {
            $originalCounselorName = $this->originalCounselor ?
                $this->originalCounselor->user->first_name . ' ' . $this->originalCounselor->user->last_name :
                'Unknown Counselor';

            $suffix = $this->referral_outcome === 'rejected' ? ' (Rejected)' : '';
            return "Referred from {$originalCounselorName}{$suffix}";
        }
        return ucfirst($this->status);
    }

    // Helper method to get status with referral context based on counselor perspective
    public function getStatusWithReferralContext($counselorId): string
    {
        $statusLabels = [
            'reschedule_requested' => 'Reschedule Requested (Pending Student Approval)',
            'reschedule_rejected' => 'Rejected by Student',
            'rescheduled' => 'Scheduled (Rescheduled)',
        ];

        if ($this->is_referred) {
            if ((int) $this->original_counselor_id === (int) $counselorId) {
                return $this->getReferralDisplayForReferringCounselor();
            }
            if ((int) $this->referred_to_counselor_id === (int) $counselorId) {
                return $this->getReferralDisplayForReceivingCounselor();
            }
        }
        return $statusLabels[$this->status] ?? ucfirst($this->status);
    }




/**
 * Check if a counselor can manage this appointment (including cross-college referrals)
 */
// In App\Models\Appointment.php - make sure this method exists and works
public function canBeManagedBy($counselorId): bool
{
    Log::info('canBeManagedBy check', [
        'appointment_id' => $this->id,
        'counselor_id' => $this->counselor_id,
        'referred_to_counselor_id' => $this->referred_to_counselor_id,
        'status' => $this->status,
        'checking_counselor_id' => $counselorId,
        'is_current_counselor' => $this->counselor_id == $counselorId,
        'is_referred_to_counselor' => $this->status === 'referred' && $this->referred_to_counselor_id == $counselorId
    ]);

    // If the counselor is the current assigned counselor, they can manage it
    if ($this->counselor_id == $counselorId) {
        return true;
    }

    // If the appointment was referred to this counselor, they can manage it
    if ($this->status === 'referred' && $this->referred_to_counselor_id == $counselorId) {
        return true;
    }

    // If the counselor is the original counselor of a referred appointment
    if ($this->status === 'referred' && $this->original_counselor_id == $counselorId) {
        return true;
    }

    return false;
}

public function canBeViewedBy($counselorId): bool
{
    if ($this->counselor_id == $counselorId) {
        return true;
    }

    if ($this->is_referred && $this->referred_to_counselor_id == $counselorId) {
        return true;
    }

    if ($this->is_referred && $this->original_counselor_id == $counselorId) {
        return true;
    }

    return false;
}

/**
 * Get the effective counselor (who should manage it now)
 */
public function getEffectiveCounselorId()
{
    // For referred appointments, the referred_to_counselor_id is the one who should manage it
    if ($this->status === 'referred' && $this->referred_to_counselor_id) {
        return $this->referred_to_counselor_id;
    }

    // For normal appointments, use the current counselor_id
    return $this->counselor_id;
}
}
