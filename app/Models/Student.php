<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'student_id',
        'year_level',
        'course',
        'college_id',
    ];

    /**
     * Get the user that owns the student profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the college that the student belongs to.
     */
    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    /**
     * Get the events that the student has registered for.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_registrations')
                    ->withPivot('registered_at', 'status')
                    ->withTimestamps();
    }

    /**
     * Get the event registrations for the student.
     */
    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Check if student is registered for a specific event.
     */
    public function isRegisteredForEvent(Event $event): bool
    {
        return $this->events()
            ->where('event_id', $event->id)
            ->where('status', 'registered')
            ->exists();
    }

    /**
     * Get upcoming event registrations.
     */
    public function upcomingRegistrations()
    {
        return $this->eventRegistrations()
            ->with('event')
            ->registered()
            ->upcoming()
            ->get();
    }

    /**
     * Get registration count for student.
     */
    public function getRegistrationCountAttribute(): int
    {
        return $this->eventRegistrations()->registered()->count();
    }

    /**
     * Get full student information with user details.
     */
    public function getFullInfoAttribute(): array
    {
        return [
            'student_id' => $this->student_id,
            'year_level' => $this->year_level,
            'course' => $this->course,
            'college' => $this->college->name ?? null,
            'full_name' => $this->user->full_name ?? null,
            'email' => $this->user->email ?? null,
        ];
    }

    public function lastSessionNote()
{
    return $this->hasOne(SessionNote::class)->latest('session_date');
}
public function sessionNotes()
    {
        return $this->hasMany(SessionNote::class);
    }
}
