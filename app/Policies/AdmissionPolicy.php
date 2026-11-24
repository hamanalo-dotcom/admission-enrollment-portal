<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admission;

class AdmissionPolicy
{
    public function view(User $user, Admission $admission): bool
    {
        // Registrar can always view all admissions
        if ($user->role === User::ROLE_REGISTRAR) {
            return true;
        }

        // Only a student who owns the admission can view it
        return $user->role === User::ROLE_STUDENT && $user->id === $admission->user_id;
    }

    public function update(User $user, Admission $admission): bool
    {
        // Registrar can update
        if ($user->role === User::ROLE_REGISTRAR) {
            return true;
        }

        // Only the student who owns the admission can update it
        return $user->role === User::ROLE_STUDENT && $user->id === $admission->user_id;
    }

    public function create(User $user): bool
    {
        // Students can create their admission; registrars as needed
        return in_array($user->role, [User::ROLE_STUDENT, User::ROLE_REGISTRAR]);
    }
}


