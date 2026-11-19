<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register'); // gumawa ka ng 'resources/views/auth/register.blade.php'
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/'
            ],
            'birthdate' => 'required|date',
            'sex' => 'required|in:male,female,other',
        ]);

        // Auto-detect role based on email domain
        $emailDomain = strtolower(substr(strrchr($validated['email'], "@"), 1));
        $role = 'student'; // default role
        
        if ($emailDomain === 'deped.gov.ph') {
            $role = 'teacher';
        } else {
            $role = 'student';
        }

        // Students must create accounts via Google sign-in (after admission approval)
        if ($role === 'student') {
            return back()
                ->withErrors([
                    'email' => 'Student accounts must be created using Google Sign-In after your admission is approved. Please click "Sign in with Google" to continue.',
                ])
                ->withInput();
        }

        $user = User::create([
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'] ?? '',
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'birthdate' => $validated['birthdate'],
            'sex' => $validated['sex'],
            'contact_no' => '', // Can be updated later in profile
            'emergency_contact' => '', // Can be updated later in profile
            'address' => '', // Can be updated later in profile
        ]);

        // Optional: automatic login after registration
        \Illuminate\Support\Facades\Auth::login($user);

        // Send email verification link
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }

        return redirect()->route('verification.notice')->with('success', 'Registration successful! Please verify your email.');
    }
}
