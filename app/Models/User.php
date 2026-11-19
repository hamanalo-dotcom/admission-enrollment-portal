<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    // Role constants for safer RBAC checks
    public const ROLE_ADMIN = 'admin';
    public const ROLE_REGISTRAR = 'registrar';
    public const ROLE_TEACHER = 'teacher';
    public const ROLE_STUDENT = 'student';

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'password',
        'role',
        'birthdate',
        'sex',
        // 'year',
        // 'section',
        'contact_no',
        'emergency_contact',
        'address',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthdate' => 'date',
    ];

    public function admissions()
    {
        return $this->hasMany(Admission::class, 'user_id');
    }

    /**
     * Get teacher section assignments (for teachers)
     */
    public function teacherAssignments()
    {
        return $this->hasMany(TeacherSectionAssignment::class, 'teacher_id');
    }

    /**
     * Get grades received (for students)
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    /**
     * Get grades given (for teachers)
     */
    public function gradesGiven()
    {
        return $this->hasMany(Grade::class, 'teacher_id');
    }

    /**
     * Get the formatted display name for the user
     * Adds "Teacher" prefix for teachers
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->firstname . ' ' . $this->lastname;
        
        if ($this->role === self::ROLE_TEACHER) {
            return 'Teacher ' . $name;
        }
        
        return $name;
    }

    /**
     * Get the formatted full name with title
     */
    public function getFormattedNameAttribute(): string
    {
        return $this->display_name;
    }
}
