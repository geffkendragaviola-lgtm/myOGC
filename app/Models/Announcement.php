<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that created the announcement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
}
