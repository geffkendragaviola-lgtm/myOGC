<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image',
        'start_date',
        'end_date',
        'is_active',
        'for_all_colleges'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'for_all_colleges' => 'boolean',
    ];

    protected $appends = [
        'image_url',
        'status',
        'status_color',
        'is_completed'
    ];

    /**
     * Get the user that created the announcement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the colleges targeted by this announcement.
     */
    public function colleges(): BelongsToMany
    {
        return $this->belongsToMany(College::class, 'announcement_college');
    }

    /**
     * Get image URL attribute
     */
public function getImageUrlAttribute(): ?string
{
    if (!$this->image) {
        return null;
    }

    // Check if it's already a full URL
    if (filter_var($this->image, FILTER_VALIDATE_URL)) {
        return $this->image;
    }

    // Check if file exists
    if (Storage::disk('public')->exists($this->image)) {
        return asset('storage/' . $this->image);
    }

    return null;
}
    /**
     * Scope a query to only include active announcements.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->where('start_date', '<=', now())
                          ->orWhereNull('start_date');
                    })->where(function($q) {
                        $q->where('end_date', '>=', now())
                          ->orWhereNull('end_date');
                    });
    }

    /**
     * Scope a query to only include announcements by counselor.
     */
    public function scopeByCounselor($query, $counselorId)
    {
        return $query->where('user_id', $counselorId);
    }

    /**
     * Scope a query to only include announcements for a specific college.
     */
    public function scopeForCollege($query, $collegeId)
    {
        return $query->where(function($q) use ($collegeId) {
            $q->where('for_all_colleges', true)
              ->orWhereHas('colleges', function($q) use ($collegeId) {
                  $q->where('college_id', $collegeId);
              });
        });
    }

    /**
     * Get the status of the announcement.
     */
    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        $now = now();
        if ($this->start_date && $this->start_date->gt($now)) {
            return 'scheduled';
        }

        if ($this->end_date && $this->end_date->lt($now)) {
            return 'expired';
        }

        return 'active';
    }

    /**
     * Get status color for display.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'green',
            'scheduled' => 'blue',
            'expired' => 'gray',
            'inactive' => 'red',
            default => 'gray'
        };
    }

    /**
     * Check if announcement is completed (ended and inactive)
     */
    public function getIsCompletedAttribute()
    {
        return !$this->is_active && $this->end_date && $this->end_date->lt(now());
    }

    /**
     * Check if announcement is available for a specific college
     */
    public function isAvailableForCollege($collegeId): bool
    {
        if ($this->for_all_colleges) {
            return true;
        }

        return $this->colleges()->where('college_id', $collegeId)->exists();
    }

    /**
     * Get targeted colleges names as string
     */
    public function getTargetedCollegesAttribute(): string
    {
        if ($this->for_all_colleges) {
            return 'All Colleges';
        }

        return $this->colleges->pluck('name')->join(', ');
    }
}
