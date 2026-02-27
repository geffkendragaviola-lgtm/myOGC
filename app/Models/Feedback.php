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
        'target_counselor_id',
        'service_availed',
        'satisfaction_rating',
        'comments',
        'is_anonymous',
        'share_mobile',
        'personnel_name',
        'cc1',
        'cc2',
        'cc3',
        'sqd0',
        'sqd1',
        'sqd2',
        'sqd3_1',
        'sqd3_2',
        'sqd4',
        'sqd5',
        'sqd6',
        'sqd7_1',
        'sqd7_2',
        'sqd7_3',
        'sqd8',
        'sqd9',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'share_mobile' => 'boolean',
    ];

    /**
     * Get the user that submitted the feedback.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function targetCounselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class, 'target_counselor_id');
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
