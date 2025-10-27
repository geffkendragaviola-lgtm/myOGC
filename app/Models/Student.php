<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'msu_sase_score',
        'academic_year',
        'profile_picture',
        'student_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'msu_sase_score' => 'decimal:2',
    ];
    public function getProfilePictureUrlAttribute(): string
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }

        // Default profile picture
        return asset('images/default-profile.png');
    }
    public function getProfilePicturePathAttribute(): string
    {
        return $this->profile_picture ? 'storage/' . $this->profile_picture : '';
    }

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
     * Get the personal data for the student.
     */
    public function personalData(): HasOne
    {
        return $this->hasOne(StudentPersonalData::class);
    }

    /**
     * Get the family data for the student.
     */
    public function familyData(): HasOne
    {
        return $this->hasOne(StudentFamilyData::class);
    }

    /**
     * Get the academic data for the student.
     */
    public function academicData(): HasOne
    {
        return $this->hasOne(StudentAcademicData::class);
    }

    /**
     * Get the learning resources data for the student.
     */
    public function learningResources(): HasOne
    {
        return $this->hasOne(StudentLearningResources::class);
    }

    /**
     * Get the psychosocial data for the student.
     */
    public function psychosocialData(): HasOne
    {
        return $this->hasOne(StudentPsychosocialData::class);
    }

    /**
     * Get the needs assessment data for the student.
     */
    public function needsAssessment(): HasOne
    {
        return $this->hasOne(StudentNeedsAssessment::class);
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
     * Get the session notes for the student.
     */
    public function sessionNotes(): HasMany
    {
        return $this->hasMany(SessionNote::class);
    }

    /**
     * Get the counseling sessions for the student.
     */

    /**
     * Get the last session note for the student.
     */
    public function lastSessionNote(): HasOne
    {
        return $this->hasOne(SessionNote::class)->latest('session_date');
    }

    /**
     * Get the appointments for the student.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
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
            'student_status' => $this->student_status,
            'academic_year' => $this->academic_year,
        ];
    }

    /**
     * Check if student has completed all profile sections.
     */
    public function getProfileCompletionAttribute(): array
    {
        $sections = [
            'personal' => (bool) $this->personalData,
            'family' => (bool) $this->familyData,
            'academic' => (bool) $this->academicData,
            'learning' => (bool) $this->learningResources,
            'psychosocial' => (bool) $this->psychosocialData,
            'needs' => (bool) $this->needsAssessment,
        ];

        $completed = array_filter($sections);
        $percentage = round((count($completed) / count($sections)) * 100);

        return [
            'sections' => $sections,
            'completed_count' => count($completed),
            'total_sections' => count($sections),
            'percentage' => $percentage,
            'is_complete' => $percentage === 100,
        ];
    }

    /**
     * Get student's full name from user relationship.
     */
    public function getFullNameAttribute(): string
    {
        return $this->user->full_name ?? '';
    }

    /**
     * Get student's email from user relationship.
     */
    public function getEmailAttribute(): string
    {
        return $this->user->email ?? '';
    }

    /**
     * Get student's phone number from user relationship.
     */
    public function getPhoneNumberAttribute(): string
    {
        return $this->user->phone_number ?? '';
    }

    /**
     * Scope a query to only include students by year level.
     */
    public function scopeByYearLevel($query, $yearLevel)
    {
        return $query->where('year_level', $yearLevel);
    }

    /**
     * Scope a query to only include students by course.
     */
    public function scopeByCourse($query, $course)
    {
        return $query->where('course', 'like', "%{$course}%");
    }

    /**
     * Scope a query to only include students by college.
     */
    public function scopeByCollege($query, $collegeId)
    {
        return $query->where('college_id', $collegeId);
    }

    /**
     * Scope a query to only include students by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('student_status', $status);
    }

    /**
     * Get students who need immediate counseling.
     */
    public function scopeNeedsImmediateCounseling($query)
    {
        return $query->whereHas('psychosocialData', function ($q) {
            $q->where('needs_immediate_counseling', true);
        });
    }

    /**
     * Get students with incomplete profiles.
     */
    public function scopeWithIncompleteProfiles($query)
    {
        return $query->where(function ($q) {
            $q->whereDoesntHave('personalData')
              ->orWhereDoesntHave('familyData')
              ->orWhereDoesntHave('academicData')
              ->orWhereDoesntHave('learningResources')
              ->orWhereDoesntHave('psychosocialData')
              ->orWhereDoesntHave('needsAssessment');
        });
    }

    /**
     * Get upcoming appointments for the student.
     */
    public function getUpcomingAppointments()
    {
        return $this->appointments()
            ->where('appointment_date', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('appointment_date')
            ->get();
    }

    /**
     * Get recent counseling sessions for the student.
     */
    public function getRecentSessions($limit = 5)
    {
        return $this->counselingSessions()
            ->with('counselor.user')
            ->orderBy('session_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if student has any urgent needs.
     */
    public function hasUrgentNeeds(): bool
    {
        return $this->psychosocialData && $this->psychosocialData->needs_immediate_counseling;
    }

    /**
     * Get student's primary concerns for counseling.
     */



public function getCounselingConcerns()
{
    $concerns = [];

    // Check if psychosocial data exists and future_counseling_concerns is not empty
    if ($this->psychosocialData && !empty($this->psychosocialData->future_counseling_concerns)) {
        // If it's a string, convert to array with one element
        if (is_string($this->psychosocialData->future_counseling_concerns)) {
            $concerns = [$this->psychosocialData->future_counseling_concerns];
        }
        // If it's already an array, use it directly
        elseif (is_array($this->psychosocialData->future_counseling_concerns)) {
            $concerns = $this->psychosocialData->future_counseling_concerns;
        }
    }

    // Add other concern sources here if needed
    if ($this->needsAssessment && !empty($this->needsAssessment->improvement_needs)) {
        if (is_array($this->needsAssessment->improvement_needs)) {
            $concerns = array_merge($concerns, $this->needsAssessment->improvement_needs);
        }
    }

    return $concerns;
}
}
