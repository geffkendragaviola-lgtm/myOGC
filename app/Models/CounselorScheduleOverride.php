<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselorScheduleOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'counselor_id',
        'date',
        'is_closed',
        'time_slots',
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
        'time_slots' => 'array',
    ];

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class);
    }
}
