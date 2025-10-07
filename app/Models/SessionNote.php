<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'counselor_id',
        'student_id',
        'notes',
        'follow_up_actions',
        'session_date',
        'session_type',
        'mood_level',
        'requires_follow_up',
        'next_session_date'
    ];

    protected $casts = [
        'session_date' => 'date',
        'next_session_date' => 'date',
        'requires_follow_up' => 'boolean',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Remove or modify this method to prevent automatic session note creation
    /**
     * Check if this session note has a scheduled follow-up appointment
     */
    public function hasScheduledFollowUp()
    {
        return $this->requires_follow_up && $this->next_session_date;
    }

    /**
     * Get the follow-up appointment (if any)
     */
    public function followUpAppointment()
    {
        return Appointment::where('student_id', $this->student_id)
            ->where('counselor_id', $this->counselor_id)
            ->where('appointment_date', $this->next_session_date)
            ->where('notes', 'like', "%follow-up%session notes #{$this->id}%")
            ->first();
    }

    public static function getSessionTypes(): array
    {
        return [
            'initial' => 'Initial Session',
            'follow_up' => 'Follow-up Session',
            'crisis' => 'Crisis Intervention',
            'regular' => 'Regular Session',
        ];
    }

    public static function getMoodLevels(): array
    {
        return [
            'very_low' => 'Very Low',
            'low' => 'Low',
            'neutral' => 'Neutral',
            'good' => 'Good',
            'very_good' => 'Very Good',
        ];
    }

    public function getSessionTypeLabelAttribute(): string
    {
        return self::getSessionTypes()[$this->session_type] ?? ucfirst($this->session_type);
    }

    public function getMoodLevelLabelAttribute(): ?string
    {
        return $this->mood_level ? self::getMoodLevels()[$this->mood_level] : null;
    }
}
