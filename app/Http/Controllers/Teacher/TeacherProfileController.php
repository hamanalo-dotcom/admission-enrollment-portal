<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeacherProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('teacher.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'birthdate' => ['required', 'date'],
            'sex' => ['required', 'in:male,female,other'],
            'contact_no' => ['required', 'string', 'max:255'],
            'emergency_contact' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:4096'],
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            // Store new profile picture
            $validated['profile_picture'] = $request->file('profile_picture')->store('uploads/profile_pictures', 'public');
        } else {
            // Keep existing profile picture if not uploading new one
            unset($validated['profile_picture']);
        }

        // Remove password from update if not provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('teacher.profile.edit')->with('success', 'Profile updated successfully!');
    }
}

