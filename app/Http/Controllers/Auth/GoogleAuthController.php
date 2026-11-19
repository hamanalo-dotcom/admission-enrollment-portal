<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth for registration
     */
    public function redirectToGoogle(Request $request)
    {
        // Check if Google OAuth is configured
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('register.form')
                ->withErrors([
                    'error' => 'Google OAuth is not configured. To set it up: 1) Go to Google Cloud Console, 2) Create OAuth 2.0 credentials, 3) Add redirect URI: ' . url('/auth/google/callback') . ', 4) Add GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET to your .env file, 5) Run: php artisan config:clear'
                ]);
        }

        // Store the email from query parameter in session for verification
        if ($request->has('email')) {
            session(['registration_email' => $request->email]);
        }

        // Mark as registration flow
        session(['oauth_flow' => 'registration']);

        try {
            return Socialite::driver('google')
                ->scopes(['openid', 'profile', 'email'])
                ->redirect();
        } catch (\Exception $e) {
            return redirect()->route('register.form')
                ->withErrors(['error' => 'Failed to initiate Google authentication. Please ensure Google OAuth credentials are properly configured.']);
        }
    }

    /**
     * Redirect to Google OAuth for login
     */
    public function redirectToGoogleLogin(Request $request)
    {
        // Check if Google OAuth is configured
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            // For unified login, redirect to main login page
            $loginRoute = $request->has('role') ? 
                         ($request->get('role') === 'student' ? 'login.student' : 
                         ($request->get('role') === 'teacher' ? 'login.teacher' : 
                         ($request->get('role') === 'admin' ? 'login.admin' : 'login.registrar'))) 
                         : 'login';
            
            return redirect()->route($loginRoute)
                ->withErrors([
                    'error' => 'Google OAuth is not configured. Please contact the administrator.'
                ]);
        }

        // Store intended redirect and role (optional for unified login)
        session(['oauth_flow' => 'login']);
        if ($request->has('role')) {
            session(['oauth_role' => $request->role]);
        } else {
            // For unified login, don't set role - let the system detect it
            session()->forget('oauth_role');
        }

        try {
            return Socialite::driver('google')
                ->scopes(['openid', 'profile', 'email'])
                ->redirect();
        } catch (\Exception $e) {
            $loginRoute = $request->has('role') ? 
                         ($request->get('role') === 'student' ? 'login.student' : 
                         ($request->get('role') === 'teacher' ? 'login.teacher' : 
                         ($request->get('role') === 'admin' ? 'login.admin' : 'login.registrar'))) 
                         : 'login';
            
            return redirect()->route($loginRoute)
                ->withErrors(['error' => 'Failed to initiate Google authentication. Please ensure Google OAuth credentials are properly configured.']);
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $googleEmail = $googleUser->getEmail();
            $flow = session('oauth_flow', 'login'); // Default to login if not set
            
            // Check if user already exists
            $user = User::where('email', $googleEmail)->first();

            if ($flow === 'login') {
                // LOGIN FLOW (auto-create student if approved admission exists)
                if (!$user) {
                    // Attempt auto-provision for approved admission (student)
                    $admission = Admission::where('email', $googleEmail)
                        ->where('status', 'approved')
                        ->where('is_draft', false)
                        ->latest('id')
                        ->first();

                    if ($admission) {
                        $user = User::create([
                            'firstname' => $admission->first_name ?? $googleUser->user['given_name'] ?? '',
                            'middlename' => $admission->middle_name ?? '',
                            'lastname' => $admission->last_name ?? $googleUser->user['family_name'] ?? '',
                            'email' => $googleEmail,
                            'password' => Hash::make(Str::random(32)), // random password since Google auth
                            'role' => 'student',
                            'birthdate' => $admission->date_of_birth ?? now(),
                            'sex' => strtolower($admission->gender ?? 'other'),
                            'contact_no' => $admission->contact_no ?? '',
                            'emergency_contact' => '',
                            'address' => '',
                        ]);
                    } else {
                        $expectedRole = session('oauth_role');
                        $loginRoute = $expectedRole ? 
                                     ($expectedRole === 'student' ? 'login.student' : 
                                     ($expectedRole === 'teacher' ? 'login.teacher' : 
                                     ($expectedRole === 'admin' ? 'login.admin' : 'login.registrar'))) 
                                     : 'login';
                        
                        session()->forget(['oauth_flow', 'oauth_role']);
                        
                        return redirect()->route($loginRoute)
                            ->withErrors(['email' => 'No account found with this Google email. For students, please use Google sign-in after your admission is approved.']);
                    }
                }

                // Verify role matches (only if role was specified in the request)
                $expectedRole = session('oauth_role');
                if ($expectedRole && $user->role !== $expectedRole) {
                    // For unified login (no role specified), allow any role
                    // Only check role if it was explicitly specified
                    $loginRoute = $expectedRole === 'student' ? 'login.student' : 
                                 ($expectedRole === 'teacher' ? 'login.teacher' : 
                                 ($expectedRole === 'admin' ? 'login.admin' : 'login.registrar'));
                    
                    session()->forget(['oauth_flow', 'oauth_role']);
                    
                    return redirect()->route($loginRoute)
                        ->withErrors(['email' => 'This account is registered as ' . ucfirst($user->role) . '. Please use the correct login page.']);
                }

                // Log the user in
                Auth::login($user);
                $request->session()->regenerate();

                // Clear session
                session()->forget(['oauth_flow', 'oauth_role']);

                // Redirect based on role
                $dashboardRoute = '/student-dashboard';
                if ($user->role === 'admin') {
                    $dashboardRoute = '/admin-dashboard';
                } elseif ($user->role === 'registrar') {
                    $dashboardRoute = '/registrar-dashboard';
                } elseif ($user->role === 'teacher') {
                    $dashboardRoute = '/teacher-dashboard';
                }

                return redirect($dashboardRoute)
                    ->with('success', 'Welcome back! You are now logged in.');

            } else {
                // REGISTRATION FLOW
                $admissionEmail = session('registration_email');
                
                // Verify email matches (if admission email was provided)
                if ($admissionEmail && strtolower($googleEmail) !== strtolower($admissionEmail)) {
                    session()->forget(['registration_email', 'oauth_flow']);
                    return redirect()->route('register.form')
                        ->withErrors(['email' => 'The Google account email does not match the email used in your admission application. Please use the same email address.']);
                }

                if ($user) {
                    // User already exists, log them in instead
                    Auth::login($user);
                    session()->forget(['registration_email', 'oauth_flow']);
                    return redirect()->intended('/student-dashboard')
                        ->with('success', 'Welcome back! You are now logged in.');
                }

                // Check if there's an approved admission for this email
                $admission = Admission::where('email', $googleEmail)
                    ->where('status', 'approved')
                    ->where('is_draft', false)
                    ->latest('id')
                    ->first();

                if (!$admission) {
                    session()->forget(['registration_email', 'oauth_flow']);
                    return redirect()->route('register.form')
                        ->withErrors(['email' => 'No approved admission found for this email. Please ensure your admission has been approved before registering.']);
                }

                // Create new user account
                $user = User::create([
                    'firstname' => $admission->first_name ?? $googleUser->user['given_name'] ?? '',
                    'middlename' => $admission->middle_name ?? '',
                    'lastname' => $admission->last_name ?? $googleUser->user['family_name'] ?? '',
                    'email' => $googleEmail,
                    'password' => Hash::make(Str::random(32)), // Random password since using Google OAuth
                    'role' => 'student',
                    'birthdate' => $admission->date_of_birth ?? now(),
                    'sex' => strtolower($admission->gender ?? 'other'),
                    'contact_no' => $admission->contact_no ?? '',
                    'emergency_contact' => '',
                    'address' => '',
                ]);

                // Log the user in
                Auth::login($user);
                $request->session()->regenerate();

                // Clear session
                session()->forget(['registration_email', 'oauth_flow']);

                // Send email verification (optional, since Google already verified)
                if (method_exists($user, 'sendEmailVerificationNotification')) {
                    $user->markEmailAsVerified();
                }

                return redirect()->intended('/student-dashboard')
                    ->with('success', 'Account created successfully! Welcome to the student portal.');
            }

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            return redirect()->route($this->oauthFailureRoute())
                ->withErrors(['error' => 'Authentication session expired. Please try again.']);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorMessage = 'Google OAuth configuration error. ';
            if ($e->getResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                if ($statusCode === 400) {
                    $errorMessage .= 'Please verify that your Google OAuth credentials and redirect URI are correctly configured in Google Cloud Console.';
                } else {
                    $errorMessage .= 'HTTP Error: ' . $statusCode;
                }
            }
            return redirect()->route($this->oauthFailureRoute())
                ->withErrors(['error' => $errorMessage]);
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route($this->oauthFailureRoute())
                ->withErrors(['error' => 'An error occurred during Google authentication: ' . $e->getMessage()]);
        }
    }

    private function oauthFailureRoute(): string
    {
        if (session('oauth_flow') === 'registration') {
            return 'register.form';
        }

        return match (session('oauth_role')) {
            'student' => 'login.student',
            'teacher' => 'login.teacher',
            'admin' => 'login.admin',
            'registrar' => 'login.registrar',
            default => 'login',
        };
    }
}
