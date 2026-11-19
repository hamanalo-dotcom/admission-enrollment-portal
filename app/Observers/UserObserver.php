<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\TeacherAccountUpdateNotification;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user)
    {
        // Only send notification if:
        // 1. User is a teacher
        // 2. Changes were made by an admin (not by the teacher themselves)
        // 3. There were actual changes
        if ($user->role === User::ROLE_TEACHER && Auth::check() && Auth::user()->role === User::ROLE_ADMIN && Auth::user()->id !== $user->id) {
            $changes = $this->detectChanges($user);
            
            if (!empty($changes)) {
                $user->notify(new TeacherAccountUpdateNotification($changes, Auth::user()));
            }
        }
    }

    /**
     * Detect what fields were changed
     */
    protected function detectChanges(User $user)
    {
        $changes = [];
        
        $trackedFields = [
            'firstname',
            'middlename', 
            'lastname',
            'email',
            'contact_no',
            'emergency_contact',
            'address',
            'birthdate',
            'sex',
        ];

        foreach ($trackedFields as $field) {
            if ($user->wasChanged($field)) {
                $originalValue = $user->getOriginal($field);
                $newValue = $user->$field;
                
                $changes[$field] = [
                    'old' => $originalValue,
                    'new' => $newValue,
                ];
            }
        }

        // Don't track password changes in detail (security), but note it changed
        if ($user->wasChanged('password')) {
            $changes['password'] = ['changed' => true];
        }

        return $changes;
    }
}

