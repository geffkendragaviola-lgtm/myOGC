<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLearningResources extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'internet_access',
        'technology_gadgets',
        'internet_connectivity',
        'distance_learning_readiness',
        'learning_space_description',
    ];

    protected $casts = [
        'technology_gadgets' => 'array',
        'internet_connectivity' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
