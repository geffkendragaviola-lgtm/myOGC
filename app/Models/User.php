<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'birthdate',
        'age',
        'sex',
        'birthplace',
        'religion',
        'affiliation',
        'civil_status',
        'citizenship',
        'address',
        'phone_number',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Automatically calculate age from birthdate
     */
    public function setBirthdateAttribute($value)
    {
        $this->attributes['birthdate'] = $value;
        if ($value) {
            $this->attributes['age'] = Carbon::parse($value)->age;
        }
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Get the student record associated with the user.
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the counselor record associated with the user.
     */
    public function counselor(): HasOne
    {
        return $this->hasOne(Counselor::class);
    }

    /**
     * Get the admin record associated with the user.
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Get the role-specific profile based on user role.
     */
    public function profile()
    {
        return match($this->role) {
            'student' => $this->student,
            'counselor' => $this->counselor,
            'admin' => $this->admin,
            default => null,
        };
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the resources created by the user.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * Get the FAQs created by the user.
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(FAQ::class);
    }

    /**
     * Get the feedbacks created by the user.
     */
    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is counselor.
     */
    public function isCounselor(): bool
    {
        return $this->role === 'counselor';
    }

    /**
     * Check if user is student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Get full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
        ];
    }
}
