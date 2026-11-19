<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admission;

class AdmissionPolicy
{
    public function view(User $user, Admission $admission): bool
    {
        // Owner can always view own admission; admins/registrars can view all
        return $user->id === $admission->user_id
            || in_array($user->role, [User::ROLE_ADMIN, User::ROLE_REGISTRAR]);
    }

    public function update(User $user, Admission $admission): bool
    {
        // Admins/registrars can update
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_REGISTRAR])) {
            return true;
        }
        // Student (owner) can always update their own admissions
        // This allows students to keep their information up-to-date even after approval
        return $user->id === $admission->user_id;
    }

    public function create(User $user): bool
    {
        // Students can create their admission; admins/registrars as needed
        return in_array($user->role, [User::ROLE_STUDENT, User::ROLE_ADMIN, User::ROLE_REGISTRAR]);
    }
}


