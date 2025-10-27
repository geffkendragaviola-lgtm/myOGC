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
        'is_active',
        'is_required',
        'for_all_colleges',
        'image'
    ];

    protected $casts = [
        'event_start_date' => 'date',
        'event_end_date' => 'date',
        'is_active' => 'boolean',
        'is_required' => 'boolean',
        'for_all_colleges' => 'boolean',
    ];

    protected $appends = [
        'image_url',
        'thumbnail_url',
        'time_range',
        'date_range',
        'is_upcoming',
        'is_registration_open',
        'available_slots',
        'registered_count',
        'registration_rate'
    ];

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/events/' . $this->image);
        }

        // Default event images based on type
        $defaultImages = [
            'webinar' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'workshop' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'seminar' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'activity' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'conference' => 'https://images.unsplash.com/photo-1531058020387-3be344556be6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
        ];

        return $defaultImages[$this->type] ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
    }

    /**
     * Get thumbnail image URL
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/events/thumbnails/' . $this->image);
        }
        return $this->image_url;
    }

    /**
     * Register required students automatically
     */
    public function registerRequiredStudents(): void
    {
        if (!$this->is_required || !$this->is_registration_open) {
            return;
        }

        $query = Student::query();

        if ($this->for_all_colleges) {
            // Get all students for all colleges
            $students = $query->with('user')->get();
        } else {
            // Get students only from the specified colleges
            $students = $query->whereIn('college_id', $this->colleges->pluck('id'))
                            ->with('user')
                            ->get();
        }

        foreach ($students as $student) {
            // Check if student is already registered
            $existingRegistration = $this->getStudentRegistration($student);

            if (!$existingRegistration && $this->hasAvailableSlots()) {
                EventRegistration::create([
                    'event_id' => $this->id,
                    'student_id' => $student->id,
                    'registered_at' => now(),
                    'status' => 'registered'
                ]);
            }
        }
    }

    /**
     * Check if cancellation is allowed (24 hours before event start)
     */
public function isCancellationAllowed(): bool
{
    // Combine the date and time properly
    $eventDateTime = Carbon::parse($this->event_start_date->format('Y-m-d') . ' ' . $this->start_time);
    return now()->diffInHours($eventDateTime, false) >= 24;
}

    /**
     * Check if student can re-register after cancellation
     */
    public function canReRegister(Student $student): bool
    {
        $previousRegistration = $this->getStudentRegistration($student);

        return $previousRegistration &&
               $previousRegistration->status === 'cancelled' &&
               $this->hasAvailableSlots() &&
               $this->is_active &&
               $this->is_registration_open &&
               !$this->isRequiredForStudent($student);
    }

    /**
     * Get cancellation cutoff time
     */
public function getCancellationCutoffTime(): string
{
    // Combine the date and time properly
    $eventDateTime = Carbon::parse($this->event_start_date->format('Y-m-d') . ' ' . $this->start_time);
    return $eventDateTime->subHours(24)->format('M j, Y g:i A');
}

    /**
     * Check if automatic registration should be triggered
     */
    public function shouldAutoRegister(): bool
    {
        return $this->is_required &&
               $this->is_active &&
               $this->is_upcoming &&
               $this->hasAvailableSlots();
    }

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'event_registrations')
                    ->withPivot('registered_at', 'status')
                    ->withTimestamps();
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function colleges(): BelongsToMany
    {
        return $this->belongsToMany(College::class, 'event_college');
    }

    public function activeRegistrations(): HasMany
    {
        return $this->registrations()->where('status', 'registered');
    }

    /**
     * Scopes
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_end_date', '>=', now()->toDateString())
                     ->where('is_active', true)
                     ->orderBy('event_start_date')
                     ->orderBy('start_time');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCollege($query, $collegeId)
    {
        return $query->where(function($q) use ($collegeId) {
            $q->where('for_all_colleges', true)
              ->orWhereHas('colleges', function($q) use ($collegeId) {
                  $q->where('college_id', $collegeId);
              });
        });
    }

    public function scopeRequiredForCollege($query, $collegeId)
    {
        return $query->where('is_required', true)
                     ->where(function($q) use ($collegeId) {
                         $q->where('for_all_colleges', true)
                           ->orWhereHas('colleges', function($q) use ($collegeId) {
                               $q->where('college_id', $collegeId);
                           });
                     });
    }

    /**
     * Business Logic Methods
     */
    public function hasAvailableSlots(): bool
    {
        if (is_null($this->max_attendees)) {
            return true;
        }
        return $this->activeRegistrations()->count() < $this->max_attendees;
    }

    public function getAvailableSlotsAttribute(): int
    {
        if (is_null($this->max_attendees)) {
            return 999;
        }
        $registeredCount = $this->activeRegistrations()->count();
        return max(0, $this->max_attendees - $registeredCount);
    }

    public function getRegisteredCountAttribute(): int
    {
        return $this->activeRegistrations()->count();
    }

    /**
     * Check if student is registered (any active registration)
     */
    public function isRegisteredByStudent(Student $student): bool
    {
        return $this->registrations()
            ->where('student_id', $student->id)
            ->where('status', 'registered')
            ->exists();
    }

    /**
     * Get student's registration (any status)
     */
    public function getStudentRegistration(Student $student): ?EventRegistration
    {
        return $this->registrations()
            ->where('student_id', $student->id)
            ->first();
    }

    /**
     * Check if event is available for a student's college
     */
    public function isAvailableForStudent(Student $student): bool
    {
        if ($this->for_all_colleges) {
            return true;
        }

        return $this->colleges()->where('college_id', $student->college_id)->exists();
    }

    /**
     * Check if event is required for a student
     */
    public function isRequiredForStudent(Student $student): bool
    {
        if (!$this->is_required) {
            return false;
        }

        if ($this->for_all_colleges) {
            return true;
        }

        return $this->colleges()->where('college_id', $student->college_id)->exists();
    }

    /**
     * Attribute Accessors
     */
    public function getTimeRangeAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('g:i A') . ' - ' .
               Carbon::parse($this->end_time)->format('g:i A');
    }

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

    public function getIsUpcomingAttribute(): bool
    {
        return Carbon::parse($this->event_end_date)->isFuture();
    }

    public function getIsRegistrationOpenAttribute(): bool
    {
        return $this->is_active && $this->is_upcoming;
    }

    public function getRegistrationRateAttribute(): float
    {
        if (is_null($this->max_attendees) || $this->max_attendees === 0) {
            return 0;
        }
        return ($this->registered_count / $this->max_attendees) * 100;
    }

    /**
     * Get registration statistics
     */
    public function getRegistrationStatistics(): array
    {
        $registrations = $this->registrations;
        return [
            'total' => $registrations->count(),
            'registered' => $registrations->where('status', 'registered')->count(),
            'attended' => $registrations->where('status', 'attended')->count(),
            'cancelled' => $registrations->where('status', 'cancelled')->count(),
        ];
    }
}

