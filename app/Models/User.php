<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'instructor_status',
        'instructor_application',
        'instructor_applied_at',
        'instructor_reviewed_at',
        'instructor_reviewed_by',
        'instructor_review_note',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'instructor_application' => 'array',
            'instructor_applied_at' => 'datetime',
            'instructor_reviewed_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isInstructorApproved(): bool
    {
        return $this->role === 'instructor' && $this->instructor_status === 'approved';
    }
}
