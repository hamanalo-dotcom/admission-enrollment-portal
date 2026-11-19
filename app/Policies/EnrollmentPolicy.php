<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Enrollment;

class EnrollmentPolicy
{
    public function view(User $user, Enrollment $enrollment): bool
    {
        return $user->id === $enrollment->user_id || in_array($user->role, [User::ROLE_ADMIN, User::ROLE_REGISTRAR, User::ROLE_TEACHER]);
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        // Admin and registrar can always update
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_REGISTRAR])) {
            return true;
        }
        
        // Students can update their own enrollments if status is pending or cancelled
        if ($user->id === $enrollment->user_id && in_array($enrollment->status, ['pending', 'cancelled'])) {
            return true;
        }
        
        return false;
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        // Admin and registrar can always delete
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_REGISTRAR])) {
            return true;
        }
        
        // Students can delete their own enrollments if status is pending or cancelled
        if ($user->id === $enrollment->user_id && in_array($enrollment->status, ['pending', 'cancelled'])) {
            return true;
        }
        
        return false;
    }
}


