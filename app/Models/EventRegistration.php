<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    use HasFactory;

    protected $table = 'event_registrations';

    protected $fillable = [
        'event_id',
        'student_id',
        'registered_at',
        'status'
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    
 public function wasOverriddenByCounselor(): bool
    {
        return $this->counsellor_override && $this->override_by;
    }
    /**
     * Get the event that the registration belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the student that registered for the event.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope a query to only include active registrations.
     */
    public function scopeRegistered($query)
    {
        return $query->where('status', 'registered');
    }

    /**
     * Scope a query to only include upcoming event registrations.
     */
    public function scopeUpcoming($query)
    {
        return $query->whereHas('event', function ($query) {
            $query->where('event_end_date', '>=', now()->toDateString())
                  ->where('is_active', true);
        });
    }

    /**
     * Check if the registration is for an upcoming event.
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->event->is_upcoming;
    }

    /**
     * Mark registration as attended.
     */
    public function markAsAttended(): bool
    {
        return $this->update(['status' => 'attended']);
    }

    /**
     * Cancel the registration.
     */
    public function cancel(): bool
    {
        return $this->update(['status' => 'cancelled']);
    }
}
