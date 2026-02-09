<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPersonalData extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'nickname',
        'home_address',
        'stays_with',
        'working_student',
        'talents_skills',
        'leisure_activities',
        'serious_medical_condition',
        'physical_disability',
        'sex_identity',
        'romantic_attraction',
    ];

    protected $casts = [
        'talents_skills' => 'array',
        'leisure_activities' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
