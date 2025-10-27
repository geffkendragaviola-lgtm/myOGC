<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAcademicData extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'shs_gpa',
        'is_scholar',
        'scholarship_type',
        'school_last_attended',
        'school_address',
        'shs_track',
        'shs_strand',
        'awards_honors',
        'student_organizations',
        'co_curricular_activities',
        'career_option_1',
        'career_option_2',
        'career_option_3',
        'course_choice_by',
        'course_choice_reason',
        'msu_choice_reasons',
        'future_career_plans',
    ];

    protected $casts = [
        'awards_honors' => 'array',
        'student_organizations' => 'array',
        'co_curricular_activities' => 'array',
        'msu_choice_reasons' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
