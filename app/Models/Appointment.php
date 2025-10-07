<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'counselor_id',
        'appointment_date',
        'start_time',
        'end_time',
        'concern',
        'status',
        'notes',
        'session_note_id'
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

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
}
