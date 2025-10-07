<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'event_start_date',
        'event_end_date',
        'start_time',
        'end_time',
        'location',
        'max_attendees',
        'is_active'
    ];

    protected $casts = [
        'event_start_date' => 'date',
        'event_end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that created the event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the students registered for this event.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'event_registrations')
                    ->withPivot('registered_at', 'status')
                    ->withTimestamps();
    }

    /**
     * Get the event registrations.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the active registrations (not cancelled).
     */
    public function activeRegistrations(): HasMany
    {
        return $this->registrations()->where('status', 'registered');
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_end_date', '>=', now()->toDateString())
                     ->where('is_active', true)
                     ->orderBy('event_start_date')
                     ->orderBy('start_time');
    }

    /**
     * Scope a query to only include active events.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if the event has available slots.
     */
    public function hasAvailableSlots(): bool
    {
        if (is_null($this->max_attendees)) {
            return true;
        }

        return $this->activeRegistrations()->count() < $this->max_attendees;
    }

    /**
     * Get available slots count.
     */
    public function getAvailableSlotsAttribute(): int
    {
        if (is_null($this->max_attendees)) {
            return 999; // Large number to indicate unlimited
        }

        $registeredCount = $this->activeRegistrations()->count();
        return max(0, $this->max_attendees - $registeredCount);
    }

    /**
     * Get registered students count.
     */
    public function getRegisteredCountAttribute(): int
    {
        return $this->activeRegistrations()->count();
    }

    /**
     * Check if a student is registered for this event.
     */
    public function isRegisteredByStudent(Student $student): bool
    {
        return $this->activeRegistrations()
            ->where('student_id', $student->id)
            ->exists();
    }

    /**
     * Get the student's registration for this event.
     */
    public function getStudentRegistration(Student $student): ?EventRegistration
    {
        return $this->registrations()
            ->where('student_id', $student->id)
            ->where('status', 'registered')
            ->first();
    }

    /**
     * Get the formatted event time range.
     */
    public function getTimeRangeAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('g:i A') . ' - ' .
               Carbon::parse($this->end_time)->format('g:i A');
    }

    /**
     * Get the formatted event date range.
     */
    public function getDateRangeAttribute(): string
    {
        $start = Carbon::parse($this->event_start_date);
        $end   = Carbon::parse($this->event_end_date);

        if ($start->isSameDay($end)) {
            return $start->format('M d, Y');
        }

        if ($start->format('M Y') === $end->format('M Y')) {
            return $start->format('M d') . '–' . $end->format('d, Y');
        }

        return $start->format('M d, Y') . ' – ' . $end->format('M d, Y');
    }

    /**
     * Check if the event is upcoming.
     */
    public function getIsUpcomingAttribute(): bool
    {
        return Carbon::parse($this->event_end_date)->isFuture();
    }

    /**
     * Check if registration is open (event is active and upcoming).
     */
    public function getIsRegistrationOpenAttribute(): bool
    {
        return $this->is_active && $this->is_upcoming;
    }
    public function getRegistrationStatisticsAttribute(): array
{
    $registrations = $this->registrations;

    return [
        'total' => $registrations->count(),
        'registered' => $registrations->where('status', 'registered')->count(),
        'attended' => $registrations->where('status', 'attended')->count(),
        'cancelled' => $registrations->where('status', 'cancelled')->count(),
    ];
}

/**
 * Get registration rate percentage
 */
public function getRegistrationRateAttribute(): float
{
    if (is_null($this->max_attendees) || $this->max_attendees === 0) {
        return 0;
    }

    return ($this->registered_count / $this->max_attendees) * 100;
}
}
