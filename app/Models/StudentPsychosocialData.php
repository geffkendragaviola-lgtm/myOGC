<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPsychosocialData extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'personality_characteristics',
        'coping_mechanisms',
        'mental_health_perception',
        'had_counseling_before',
        'sought_psychologist_help',
        'problem_sharing_targets',
        'needs_immediate_counseling',
        'future_counseling_concerns',
    ];

    protected $casts = [
        'personality_characteristics' => 'array',
        'coping_mechanisms' => 'array',
        'problem_sharing_targets' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
