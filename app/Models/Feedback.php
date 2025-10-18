<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'service_availed',
        'satisfaction_rating',
        'comments',
        'is_anonymous'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
    ];

    /**
     * Get the user that submitted the feedback.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the rating label for a given rating.
     */
    public static function getRatingLabel($rating)
    {
        switch($rating) {
            case 5: return 'Very Satisfied';
            case 4: return 'Satisfied';
            case 3: return 'Neutral';
            case 2: return 'Dissatisfied';
            case 1: return 'Very Dissatisfied';
            default: return '';
        }
    }

    /**
     * Scope a query to only include anonymous feedback.
     */
    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }

    /**
     * Scope a query to only include non-anonymous feedback.
     */
    public function scopeNotAnonymous($query)
    {
        return $query->where('is_anonymous', false);
    }

    /**
 * Get the status color for a given rating
 */
public function getRatingColorAttribute()
{
    switch($this->satisfaction_rating) {
        case 5: return 'green';
        case 4: return 'blue';
        case 3: return 'yellow';
        case 2: return 'orange';
        case 1: return 'red';
        default: return 'gray';
    }
}
}
