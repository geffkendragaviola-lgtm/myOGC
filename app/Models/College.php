<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class College extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Get the students for the college.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
        public function counselors(): HasMany
    {
        return $this->hasMany(Counselor::class);
    }
}
