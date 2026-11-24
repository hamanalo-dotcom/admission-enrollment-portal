<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Enrollment;
use App\Models\TeacherSectionAssignment;

class EnrollmentPolicy
{
    public function view(User $user, Enrollment $enrollment): bool
    {
        // Registrar can always view all enrollments
        if ($user->role === User::ROLE_REGISTRAR || $user->role === 'registrar') {
            return true;
        }

        // Student can only view their own enrollment
        if ($user->role === User::ROLE_STUDENT || $user->role === 'student') {
            return $user->id === $enrollment->user_id;
        }

        // Teacher can only view enrollments of students in their assigned classes
        if ($user->role === User::ROLE_TEACHER || $user->role === 'teacher') {
            if (empty($enrollment->section) || empty($enrollment->grade_level)) {
                return false;
            }

            $query = TeacherSectionAssignment::where('teacher_id', $user->id)
                ->where('grade_level', $enrollment->grade_level)
                ->where('section', $enrollment->section);

            if (!empty($enrollment->school_year)) {
                $query->where('school_year', $enrollment->school_year);
            }

            if (!empty($enrollment->strand)) {
                // Enrollment has a strand — teacher assignment must match that strand exactly
                $query->where('strand', $enrollment->strand);
            } else {
                // Enrollment has no strand (JHS) — teacher assignment must also have no strand
                $query->whereNull('strand');
            }

            if (!empty($enrollment->tvl_branch)) {
                // Enrollment has a specific TVL branch — teacher assignment must match exactly
                $query->where('tvl_branch', $enrollment->tvl_branch);
            } else {
                // Enrollment has no TVL branch — teacher assignment must also have no branch
                $query->whereNull('tvl_branch');
            }

            return $query->exists();
        }

        // Admin and any other roles cannot view individual enrollments
        return false;
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        // Registrar can always update
        if ($user->role === User::ROLE_REGISTRAR) {
            return true;
        }
        
        // Students can update their own enrollments if status is pending or cancelled
        if ($user->role === User::ROLE_STUDENT && $user->id === $enrollment->user_id && in_array($enrollment->status, ['pending', 'cancelled'])) {
            return true;
        }
        
        return false;
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        // Registrar can always delete
        if ($user->role === User::ROLE_REGISTRAR) {
            return true;
        }
        
        // Students can delete their own enrollments if status is pending or cancelled
        if ($user->role === User::ROLE_STUDENT && $user->id === $enrollment->user_id && in_array($enrollment->status, ['pending', 'cancelled'])) {
            return true;
        }
        
        return false;
    }
}


