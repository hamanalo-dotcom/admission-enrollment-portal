<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showStudentLogin()
    {
        return view('auth.login-student');
    }

    public function showTeacherLogin()
    {
        return view('auth.login-teacher');
    }

    public function showAdminLogin()
    {
        return view('auth.login-admin');
    }

    public function showRegistrarLogin()
    {
        return view('auth.login-registrar');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if user exists with this email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Email doesn't exist - show "Incorrect Email." as specified
            throw ValidationException::withMessages([
                'email' => 'Incorrect Email.',
            ]);
        }

        // User exists, check password
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Email exists but password is wrong
            throw ValidationException::withMessages([
                'password' => 'Incorrect Password.',
            ]);
        }

        // Login successful
        $request->session()->regenerate();

        $user = Auth::user();

        // Always redirect to dashboard based on user role
        if ($user->role === 'admin') {
            return redirect('/admin-dashboard');
        } elseif ($user->role === 'registrar') {
            return redirect('/registrar-dashboard');
        } elseif ($user->role === 'teacher') {
            return redirect('/teacher-dashboard');
        } elseif ($user->role === 'student') {
            return redirect('/student-dashboard');
        } else {
            return redirect('/dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
