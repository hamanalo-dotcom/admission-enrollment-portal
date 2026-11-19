<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | San Roque High School</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="login-page">

    <!-- LEFT PANEL - HERO & BRANDING -->
    <aside class="welcome-panel">

        <a href="{{ route('home') }}" class="school-brand" title="Return to San Roque High School Home">
            <div class="school-logo-badge">
                <img src="{{ asset('img/logo.png') }}"
                     alt="San Roque High School Logo">
            </div>
            <div class="brand-text-wrap">
                <span class="brand-title">San Roque High School</span>
                <span class="brand-subtitle">Admission &amp; Enrollment Portal</span>
            </div>
        </a>

        <div class="welcome-content">
            <span class="welcome-eyebrow">WELCOME TO YOUR NEXT CHAPTER</span>

            <h1>
                Your Future<br>
                Starts <span>Here.</span>
            </h1>

            <div class="welcome-line"></div>

            <div class="portal-tag">San Roque High School Portal</div>

            <p class="welcome-description">
                Welcome to San Roque High School's online portal.
                Apply for admission, complete your enrollment, and
                stay connected throughout your academic journey.
            </p>

            <div class="feature-list">

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"
                                  stroke="currentColor" stroke-width="1.8"/>
                            <path d="M14 3v5h5M9 13h6M9 17h6"
                                  stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <strong>Online Application</strong>
                        <span>Apply for admission from anywhere, anytime.</span>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="m12 3 8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4Z"
                                  stroke="currentColor" stroke-width="1.8"
                                  stroke-linejoin="round"/>
                            <path d="m8.5 12 2.3 2.3 4.7-4.7"
                                  stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <strong>Secure Access</strong>
                        <span>Protected credentials and verified role security.</span>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="9" cy="8" r="3"
                                    stroke="currentColor" stroke-width="1.8"/>
                            <path d="M3 20v-2a6 6 0 0 1 12 0v2H3Z"
                                  stroke="currentColor" stroke-width="1.8"
                                  stroke-linejoin="round"/>
                            <path d="M16 5.5a3 3 0 0 1 0 5.8M18 14a5 5 0 0 1 3 4.5V20h-3"
                                  stroke="currentColor" stroke-width="1.8"
                                  stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <strong>Student &amp; Staff Support</strong>
                        <span>Access academic records, grades, and services.</span>
                    </div>
                </div>

            </div>
        </div>

        <footer class="welcome-footer">
            <div class="footer-motto">LEARN <span>•</span> GROW <span>•</span> BELONG</div>
            <p>&copy; {{ date('Y') }} San Roque High School. All rights reserved.</p>
        </footer>

    </aside>


    <!-- RIGHT PANEL - AUTHENTICATION FORM -->
    <main class="login-panel">

        <header class="login-header">
            <a href="{{ route('home') }}" class="mobile-brand" title="San Roque High School Home">
                <img src="{{ asset('img/logo.png') }}" alt="School logo">
                <span>San Roque High School</span>
            </a>

            <a href="{{ route('home') }}" class="back-home">
                <span aria-hidden="true">&larr;</span>
                Back to Home
            </a>
        </header>

        <div class="login-content">

            <div class="login-emblem" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M2 9 12 4l10 5-10 5L2 9Z"
                          stroke="currentColor" stroke-width="1.8"
                          stroke-linejoin="round"/>
                    <path d="M6 11v5c3.5 3 8.5 3 12 0v-5M22 9v7"
                          stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <span class="login-eyebrow">PORTAL AUTHENTICATION</span>

            <h1>Welcome Back!</h1>

            <p class="login-description">
                Sign in to continue to the San Roque High School
                Admission and Enrollment Portal.
            </p>

            <!-- Success flash message -->
            @if (session('success') || session('status'))
                <div class="login-alert alert-success" role="alert">
                    <strong>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Success
                    </strong>
                    <p style="margin: 0;">{{ session('success') ?? session('status') }}</p>
                </div>
            @endif

            <!-- Session error message -->
            @if (session('error'))
                <div class="login-alert alert-error" role="alert">
                    <strong>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Login Error
                    </strong>
                    <p style="margin: 0;">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Validation errors -->
            @if (isset($errors) && $errors->any())
                <div class="login-alert alert-error" role="alert">
                    <strong>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            <path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Login Failed
                    </strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- GOOGLE LOGIN -->
            <a href="{{ route('login.google') }}" class="google-signin-btn" id="googleLoginBtn">
                <svg class="google-icon" viewBox="0 0 48 48" aria-hidden="true">
                    <path fill="#EA4335"
                          d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5Z"/>
                    <path fill="#4285F4"
                          d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.76 7.18l7.73 6C44.42 38.05 46.98 31.9 46.98 24.55Z"/>
                    <path fill="#FBBC05"
                          d="M10.53 28.59A14.4 14.4 0 0 1 9.75 24c0-1.59.27-3.13.76-4.59l-7.98-6.19A23.9 23.9 0 0 0 0 24c0 3.87.93 7.52 2.56 10.78l7.97-6.19Z"/>
                    <path fill="#34A853"
                          d="M24 48c6.48 0 11.93-2.13 15.91-5.8l-7.73-6c-2.14 1.45-4.89 2.3-8.18 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48Z"/>
                </svg>

                <span>Continue with Google</span>

                <span class="google-arrow" aria-hidden="true">&rarr;</span>
            </a>

            <div class="login-divider">
                <span>OR SIGN IN WITH EMAIL</span>
            </div>

            <!-- EMAIL / PASSWORD LOGIN FORM -->
            <form method="POST"
                  action="{{ route('login') }}"
                  id="loginForm"
                  class="login-form">
                @csrf

                <!-- Email Input -->
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <div class="input-field-wrap">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email address"
                            autocomplete="username"
                            required
                            autofocus
                            class="input-control @error('email') input-error @enderror"
                        >
                    </div>

                    @error('email')
                        <span class="field-error">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="input-group">
                    <div class="input-label-row">
                        <label for="password">Password</label>
                        <a href="{{ route('password.request') }}" class="forgot-password-link">
                            Forgot password?
                        </a>
                    </div>

                    <div class="input-field-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                            class="input-control with-toggle @error('password') input-error @enderror"
                        >

                        <button
                            type="button"
                            class="password-toggle-btn"
                            id="togglePasswordBtn"
                            aria-label="Show password"
                            title="Show password"
                        >
                            <!-- Eye icon (show password) -->
                            <svg id="eyeOpenIcon" viewBox="0 0 24 24" fill="none">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
                            </svg>
                            <!-- Eye slash icon (hide password) -->
                            <svg id="eyeSlashIcon" viewBox="0 0 24 24" fill="none" style="display: none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <span class="field-error">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-button" id="loginButton">
                    <span class="button-text">Log In</span>
                    <span class="button-spinner" aria-hidden="true"></span>
                </button>
            </form>

            <!-- Role-Specific Portal Quick Links -->
            <div class="role-portal-links">
                <span>Looking for a specific portal?</span>
                <div class="role-chips">
                    <a href="{{ route('login.student') }}" class="role-chip">Student Portal</a>
                    <a href="{{ route('login.teacher') }}" class="role-chip">Teacher Portal</a>
                    <a href="{{ route('login.admin') }}" class="role-chip">Admin Portal</a>
                    <a href="{{ route('login.registrar') }}" class="role-chip">Registrar Portal</a>
                </div>
            </div>

            <!-- Admission Notice -->
            <div class="admission-notice">
                <span class="notice-icon-badge">i</span>
                <p>
                    <strong>New student?</strong>
                    Complete your admission application first and wait
                    for registrar approval before enrolling.
                    <a href="{{ route('admission.create.public') }}">Apply for Admission &rarr;</a>
                </p>
            </div>

            <!-- Trust Badges -->
            <div class="security-row">
                <span class="security-badge">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    256-bit SSL Encrypted
                </span>
                <span class="security-badge">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 4L12 14.01l-3-3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Authorized Access
                </span>
            </div>

        </div>

        <footer class="login-footer">
            <span class="school-name">San Roque High School</span>
            <span>Admission &amp; Enrollment Portal</span>
        </footer>

    </main>

</div>

<script>
    // Password visibility toggle
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const eyeOpenIcon = document.getElementById('eyeOpenIcon');
    const eyeSlashIcon = document.getElementById('eyeSlashIcon');

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            if (eyeOpenIcon && eyeSlashIcon) {
                eyeOpenIcon.style.display = isPassword ? 'none' : 'block';
                eyeSlashIcon.style.display = isPassword ? 'block' : 'none';
            }
            
            togglePasswordBtn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            togglePasswordBtn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
        });
    }

    // Submit button state handling
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');

    if (loginForm && loginButton) {
        loginForm.addEventListener('submit', function () {
            if (!loginForm.checkValidity()) {
                return;
            }

            // Show loading spinner
            loginButton.classList.add('loading');
            loginButton.setAttribute('aria-busy', 'true');
        });
    }
</script>

</body>
</html>
