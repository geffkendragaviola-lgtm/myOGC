<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentNeedsAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'improvement_needs',
        'financial_assistance_needs',
        'personal_social_needs',
        'stress_responses',
        'easy_discussion_target',
        'counseling_perceptions',
    ];

    protected $casts = [
        'improvement_needs' => 'array',
        'financial_assistance_needs' => 'array',
        'personal_social_needs' => 'array',
        'stress_responses' => 'array',
        'counseling_perceptions' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
