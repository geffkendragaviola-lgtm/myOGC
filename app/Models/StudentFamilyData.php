<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFamilyData extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'father_name',
        'father_deceased',
        'father_occupation',
        'father_phone_number',
        'mother_name',
        'mother_deceased',
        'mother_occupation',
        'mother_phone_number',
        'parents_marital_status',
        'family_monthly_income',
        'guardian_name',
        'guardian_occupation',
        'guardian_phone_number',
        'guardian_relationship',
        'ordinal_position',
        'number_of_siblings',
        'home_environment_description',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
