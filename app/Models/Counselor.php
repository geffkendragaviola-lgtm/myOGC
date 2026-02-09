<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Counselor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'college_id',
        'position',
        'credentials',
        'is_head',
        'specialization',
        'availability',
        'google_calendar_id',
        'daily_booking_limit',
    ];

    protected $casts = [
        'is_head' => 'boolean',
        'availability' => 'array',
        'daily_booking_limit' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
    public function scheduleOverrides(): HasMany
    {
        return $this->hasMany(CounselorScheduleOverride::class);
    }
// Relationship to received referrals
public function receivedReferrals(): HasMany
{
    return $this->hasMany(Appointment::class, 'referred_to_counselor_id');
}
    public function getDefaultAvailability()
    {
        return [
            'monday' => ['08:00-12:00', '13:00-17:00'],
            'tuesday' => ['08:00-12:00', '13:00-17:00'],
            'wednesday' => ['08:00-12:00', '13:00-17:00'],
            'thursday' => ['08:00-12:00', '13:00-17:00'],
            'friday' => ['08:00-12:00', '13:00-17:00'],
        ];
    }

    public function getAvailability()
    {
        return $this->availability ?? $this->getDefaultAvailability();
    }

    public function getDailyBookingLimit(): int
    {
        $limit = $this->daily_booking_limit;

        if (is_null($limit)) {
            return 3;
        }

        return max(0, (int) $limit);
    }

}
