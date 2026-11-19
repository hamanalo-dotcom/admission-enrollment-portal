<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="San Roque High School Admission and Enrollment Portal - Apply for admission, check enrollment status, and access student services online.">
    <title>San Roque High School - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            margin: 0; padding: 0; box-sizing: border-box;
        }

        :root {
            --blue: #1a56b0;
            --blue-dark: #143f87;
            --blue-light: #e8f0fc;
            --text-primary: #0f1c2e;
            --text-secondary: #5a6a7e;
            --border: rgba(0,0,0,0.08);
            --bg-page: #f4f7fb;
            --bg-white: #ffffff;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-page);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* ── HEADER ── */
        .sr-header {
            background: var(--bg-white);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 62px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .sr-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .sr-logo-copy {
            min-width: 0;
        }

        .sr-logo-icon {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid rgba(26, 86, 176, 0.2);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .sr-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .sr-logo-text {
            font-size: 14px; font-weight: 600;
            color: var(--text-primary); line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sr-logo-sub {
            font-size: 11px;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sr-nav {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .sr-nav a {
            padding: 6px 12px;
            font-size: 13px; font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: background 0.15s, color 0.15s;
            white-space: nowrap;
        }

        .sr-nav a:hover {
            background: var(--bg-page);
            color: var(--text-primary);
        }

        .sr-nav a.active {
            color: var(--blue);
            background: var(--blue-light);
        }

        .sr-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-primary-sr {
            padding: 7px 16px;
            font-size: 13px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--blue);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background 0.15s, transform 0.15s;
            white-space: nowrap;
        }

        .btn-primary-sr:hover {
            background: var(--blue-dark);
            color: white;
            transform: translateY(-1px);
        }

        .btn-outline-sr {
            padding: 6px 14px;
            font-size: 13px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: transparent;
            color: var(--blue);
            border: 1px solid var(--blue);
            border-radius: var(--radius-md);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background 0.15s, transform 0.15s;
            white-space: nowrap;
        }

        .btn-outline-sr:hover {
            background: var(--blue-light);
            color: var(--blue);
            transform: translateY(-1px);
        }

        /* Hamburger */
        .sr-hamburger {
            display: none;
            flex-direction: column;
            gap: 4px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
        }

        .sr-hamburger span {
            display: block;
            width: 20px; height: 2px;
            background: var(--text-primary);
            border-radius: 2px;
            transition: all 0.2s;
        }

        .sr-hamburger.open span:nth-child(1) { transform: translateY(6px) rotate(45deg); }
        .sr-hamburger.open span:nth-child(2) { opacity: 0; }
        .sr-hamburger.open span:nth-child(3) { transform: translateY(-6px) rotate(-45deg); }

        /* Mobile nav drawer */
        .sr-mobile-menu {
            display: none;
            position: fixed;
            top: 62px; left: 0; right: 0;
            background: var(--bg-white);
            border-bottom: 1px solid var(--border);
            padding: 12px 16px 16px;
            z-index: 99;
            flex-direction: column;
            gap: 6px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        .sr-mobile-menu.open { display: flex; }

        .sr-mobile-menu a {
            padding: 11px 14px;
            font-size: 14px; font-weight: 500;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            transition: background 0.15s;
        }

        .sr-mobile-menu a:hover { background: var(--bg-page); }
        .sr-mobile-menu a.active { color: var(--blue); background: var(--blue-light); border-color: rgba(26,86,176,0.2); }

        .sr-mobile-menu .btn-primary-sr {
            justify-content: center;
            margin-top: 4px;
            padding: 10px 14px;
            font-size: 14px;
            color: #ffffff;
            border-color: transparent;
        }

        .sr-mobile-menu .btn-primary-sr:hover {
            color: #ffffff;
            background: var(--blue-dark);
        }

        .sr-mobile-menu .btn-primary-sr i {
            color: inherit;
        }

        /* ── HERO ── */
        .sr-hero {
            background-image:
                linear-gradient(135deg, rgba(20, 43, 79, 0.88), rgba(26, 86, 176, 0.78)),
                url('{{ asset("img/hs.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 64px 20px;
            position: relative;
            overflow: hidden;
        }

        .sr-hero::before {
            content: '';
            position: absolute; top: -100px; right: -100px;
            width: 400px; height: 400px; border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }

        .sr-hero::after {
            content: '';
            position: absolute; bottom: -80px; left: -80px;
            width: 300px; height: 300px; border-radius: 50%;
            background: rgba(255,255,255,0.05);
            pointer-events: none;
        }

        .sr-hero-inner {
            max-width: 860px;
            margin: 0 auto;
            position: relative; z-index: 1;
            display: flex; flex-direction: column;
            align-items: center; text-align: center;
        }

        .sr-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            font-size: 11px; font-weight: 600;
            letter-spacing: 0.07em; text-transform: uppercase;
            padding: 5px 12px; border-radius: 999px;
            margin-bottom: 20px;
        }

        .sr-hero-title {
            font-size: 38px; font-weight: 700; line-height: 1.2;
            color: white; margin-bottom: 14px;
        }

        .sr-hero-desc {
            font-size: 15px;
            color: rgba(255,255,255,0.82);
            line-height: 1.75;
            margin-bottom: 30px;
            max-width: 560px;
        }

        .sr-hero-actions {
            display: flex; gap: 10px; flex-wrap: wrap;
            justify-content: center; margin-bottom: 48px;
        }

        .sr-hero-actions .btn-hero-primary {
            padding: 11px 22px;
            font-size: 14px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: white; color: var(--blue);
            border: none; border-radius: var(--radius-md);
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 7px;
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .sr-hero-actions .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            color: var(--blue);
        }

        .sr-hero-actions .btn-hero-outline {
            padding: 10px 22px;
            font-size: 14px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: transparent; color: white;
            border: 1px solid rgba(255,255,255,0.55);
            border-radius: var(--radius-md);
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 7px;
            transition: background 0.15s, transform 0.15s;
        }

        .sr-hero-actions .btn-hero-outline:hover {
            background: rgba(255,255,255,0.12);
            color: white;
            transform: translateY(-2px);
        }

        .sr-hero-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px; width: 100%;
        }

        .sr-stat {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: var(--radius-lg);
            padding: 18px 12px; text-align: center;
        }

        .sr-stat-num { font-size: 22px; font-weight: 700; color: white; }
        .sr-stat-label { font-size: 11px; color: rgba(255,255,255,0.72); margin-top: 4px; font-weight: 500; }

        /* ── PAGE BODY ── */
        .sr-section {
            max-width: 1100px;
            margin: 0 auto;
            padding: 52px 20px;
        }

        .sr-section-label {
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.09em; text-transform: uppercase;
            color: var(--blue); margin-bottom: 6px;
        }

        .sr-section-title {
            font-size: 22px; font-weight: 700;
            color: var(--text-primary); margin-bottom: 6px;
        }

        .sr-section-desc {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 24px;
        }

        /* About cards */
        .sr-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .sr-card {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .sr-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.07);
        }

        .sr-card-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            background: var(--blue-light);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            margin-bottom: 14px;
        }

        .sr-card h3 {
            font-size: 14px; font-weight: 700;
            color: var(--text-primary); margin-bottom: 8px;
        }

        .sr-card p {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* Steps */
        .sr-steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }

        .sr-step {
            padding: 22px 18px;
            background: var(--bg-white);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            border-left: 1px solid var(--border);
        }

        .sr-step:first-child { border-radius: var(--radius-lg) 0 0 var(--radius-lg); }
        .sr-step:last-child {
            border-right: 1px solid var(--border);
            border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
        }

        .sr-step-num {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--blue); color: white;
            font-size: 12px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 12px;
        }

        .sr-step h4 { font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; }
        .sr-step p { font-size: 12px; color: var(--text-secondary); line-height: 1.65; }

        /* Divider */
        .sr-divider {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .sr-divider hr {
            border: none;
            border-top: 1px solid var(--border);
        }

        /* Contact */
        .sr-contact-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .sr-contact-card {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 22px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .sr-contact-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.07);
        }

        .sr-contact-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            background: var(--blue-light);
            margin: 0 auto 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: var(--blue);
        }

        .sr-contact-card h4 {
            font-size: 13px; font-weight: 700;
            color: var(--text-primary); margin-bottom: 6px;
        }

        .sr-contact-card p,
        .sr-contact-card a {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.6;
            text-decoration: none;
            display: block;
            word-break: break-word;
        }

        .sr-contact-card a:hover { color: var(--blue); }

        /* Footer */
        .sr-footer {
            background: var(--bg-white);
            border-top: 1px solid var(--border);
            padding: 18px 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .sr-footer p { font-size: 12px; color: var(--text-secondary); }

        .sr-footer-links { display: flex; gap: 18px; }

        .sr-footer-links a {
            font-size: 12px;
            color: var(--text-secondary);
            text-decoration: none;
        }

        .sr-footer-links a:hover { color: var(--text-primary); }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .sr-nav,
            .sr-header-actions .desktop-only { display: none; }
            .sr-hamburger { display: flex; }
            .sr-header { padding: 0 1rem; }

            .sr-hero { padding: 48px 20px; }
            .sr-hero-title { font-size: 28px; }

            .sr-hero-stats { grid-template-columns: repeat(2, 1fr); }

            .sr-cards { grid-template-columns: 1fr; }

            .sr-steps { grid-template-columns: 1fr 1fr; }
            .sr-step { border: 1px solid var(--border) !important; border-radius: 0 !important; }
            .sr-step:nth-child(1) { border-radius: var(--radius-lg) 0 0 0 !important; }
            .sr-step:nth-child(2) { border-radius: 0 var(--radius-lg) 0 0 !important; border-left: none !important; }
            .sr-step:nth-child(3) { border-top: none !important; border-radius: 0 0 0 var(--radius-lg) !important; }
            .sr-step:nth-child(4) { border-top: none !important; border-left: none !important; border-radius: 0 0 var(--radius-lg) 0 !important; }

            .sr-contact-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 560px) {
            .sr-header {
                padding: 0 0.75rem;
                height: 58px;
            }

            .sr-logo {
                gap: 8px;
                min-width: 0;
                max-width: calc(100% - 40px);
            }

            .sr-logo-icon {
                width: 30px;
                height: 30px;
                font-size: 11px;
            }

            .sr-logo-text { font-size: 12px; }
            .sr-logo-sub { font-size: 10px; }

            .sr-mobile-menu {
                top: 58px;
                padding: 10px 12px 14px;
            }

            .sr-mobile-menu a {
                font-size: 13px;
                padding: 10px 12px;
            }

            .sr-hero { padding: 40px 16px; }
            .sr-hero-title { font-size: 22px; }
            .sr-hero-desc { font-size: 14px; }
            .sr-hero-stats { grid-template-columns: repeat(2, 1fr); gap: 8px; }
            .sr-hero-actions { flex-direction: column; align-items: stretch; width: 100%; }
            .sr-hero-actions a { justify-content: center; width: 100%; }

            .sr-stat { padding: 14px 8px; }
            .sr-stat-num { font-size: 18px; }
            .sr-stat-label { font-size: 10px; }

            .sr-section { padding: 36px 16px; }
            .sr-section-title { font-size: 20px; }
            .sr-section-desc { font-size: 13px; margin-bottom: 18px; }

            .sr-card,
            .sr-contact-card,
            .sr-step {
                padding: 18px 14px;
            }

            .sr-steps { grid-template-columns: 1fr; }
            .sr-step,
            .sr-step:nth-child(1),
            .sr-step:nth-child(2),
            .sr-step:nth-child(3),
            .sr-step:nth-child(4) {
                border: 1px solid var(--border) !important;
                border-radius: 0 !important;
                border-bottom: none !important;
            }
            .sr-step:first-child { border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important; }
            .sr-step:last-child {
                border-bottom: 1px solid var(--border) !important;
                border-radius: 0 0 var(--radius-lg) var(--radius-lg) !important;
            }

            .sr-contact-grid { grid-template-columns: 1fr 1fr; }

            .sr-footer {
                flex-direction: column;
                align-items: flex-start;
                padding: 14px 16px;
            }

            .sr-footer-links {
                gap: 12px;
                flex-wrap: wrap;
                row-gap: 6px;
            }
        }

        @media (max-width: 420px) {
            .sr-logo-sub {
                display: none;
            }

            .sr-mobile-menu {
                max-height: calc(100vh - 58px);
                overflow-y: auto;
            }

            .sr-hero {
                padding: 34px 12px;
            }

            .sr-hero-title {
                font-size: 20px;
                line-height: 1.3;
            }

            .sr-hero-desc {
                font-size: 13px;
                line-height: 1.65;
                margin-bottom: 22px;
            }

            .sr-hero-stats {
                grid-template-columns: 1fr;
            }

            .sr-contact-grid {
                grid-template-columns: 1fr;
            }

            .sr-footer {
                align-items: stretch;
            }

            .sr-footer-links {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 360px) {
            .sr-header {
                padding: 0 10px;
            }

            .sr-logo-text {
                font-size: 11px;
            }

            .sr-hero-title {
                font-size: 18px;
            }

            .sr-hero-actions .btn-hero-primary,
            .sr-hero-actions .btn-hero-outline {
                font-size: 13px;
                padding: 10px 14px;
            }

            .sr-section {
                padding: 32px 12px;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="sr-header">
        <a href="{{ route('home') }}" class="sr-logo">
            <div class="sr-logo-icon">
                <img src="{{ asset('img/logo.png') }}" alt="San Roque High School Logo">
            </div>
            <div class="sr-logo-copy">
                <div class="sr-logo-text">San Roque High School</div>
                <div class="sr-logo-sub">Admission &amp; Enrollment Portal</div>
            </div>
        </a>

        <nav class="sr-nav">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('admission.create.public') }}" class="{{ request()->routeIs('admission.create.public') ? 'active' : '' }}">Admission</a>
            <a href="#about" onclick="event.preventDefault(); document.getElementById('about').scrollIntoView({behavior:'smooth'});">About</a>
            <a href="#contact" onclick="event.preventDefault(); document.getElementById('contact').scrollIntoView({behavior:'smooth'});">Contact</a>
            <a href="{{ route('admission.status') }}" class="{{ request()->routeIs('admission.status') ? 'active' : '' }}">Admission Status</a>
        </nav>

        <div class="sr-header-actions">
            @auth
                @php
                    $dashboardRoute = '/dashboard';
                    if (Auth::user()->role === 'admin') {
                        $dashboardRoute = '/admin-dashboard';
                    } elseif (Auth::user()->role === 'registrar') {
                        $dashboardRoute = '/registrar-dashboard';
                    } elseif (Auth::user()->role === 'teacher') {
                        $dashboardRoute = '/teacher-dashboard';
                    } elseif (Auth::user()->role === 'student') {
                        $dashboardRoute = '/student-dashboard';
                    }
                @endphp
                <a href="{{ url($dashboardRoute) }}" class="btn-primary-sr desktop-only">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-primary-sr desktop-only">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            @endauth

            <button class="sr-hamburger" id="hamburger" aria-label="Toggle navigation" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <!-- Mobile nav drawer -->
    <div class="sr-mobile-menu" id="mobileMenu">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home fa-fw" style="margin-right:8px;"></i> Home
        </a>
        <a href="{{ route('admission.create.public') }}" class="{{ request()->routeIs('admission.create.public') ? 'active' : '' }}">
            <i class="fas fa-file-signature fa-fw" style="margin-right:8px;"></i> Admission
        </a>
        <a href="#about" id="mobileAbout">
            <i class="fas fa-school fa-fw" style="margin-right:8px;"></i> About
        </a>
        <a href="#contact" id="mobileContact">
            <i class="fas fa-address-card fa-fw" style="margin-right:8px;"></i> Contact
        </a>
        <a href="{{ route('admission.status') }}" class="{{ request()->routeIs('admission.status') ? 'active' : '' }}">
            <i class="fas fa-search fa-fw" style="margin-right:8px;"></i> Admission Status
        </a>
        @auth
            <a href="{{ url($dashboardRoute) }}" class="btn-primary-sr">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-primary-sr">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        @endauth
    </div>

    <!-- Hero -->
    <section class="sr-hero">
        <div class="sr-hero-inner">
            <div class="sr-badge">
                <i class="fas fa-check-circle" style="font-size:11px;"></i>
                Official Portal
            </div>
            <h1 class="sr-hero-title">Admissions and Enrollment,<br>Simplified for Every Learner.</h1>
            <p class="sr-hero-desc">
                Submit applications online, track admission progress, and stay informed through a secure and easy-to-use digital portal.
            </p>
            <div class="sr-hero-actions">
                <a href="{{ route('admission.create.public') }}" class="btn-hero-primary">
                    <i class="fas fa-file-signature"></i> Apply for Admission
                </a>
                <a href="{{ route('admission.status') }}" class="btn-hero-outline">
                    <i class="fas fa-search"></i> Check Status
                </a>
            </div>
            <div class="sr-hero-stats">
                <div class="sr-stat">
                    <div class="sr-stat-num">20+</div>
                    <div class="sr-stat-label">Years of service</div>
                </div>
                <div class="sr-stat">
                    <div class="sr-stat-num">100%</div>
                    <div class="sr-stat-label">Digital enrollment</div>
                </div>
                <div class="sr-stat">
                    <div class="sr-stat-num">K–12</div>
                    <div class="sr-stat-label">Curriculum aligned</div>
                </div>
                <div class="sr-stat">
                    <div class="sr-stat-num">DepEd</div>
                    <div class="sr-stat-label">Accredited school</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <div id="about" style="background: var(--bg-page);">
        <div class="sr-section">
            <div class="sr-section-label">About</div>
            <div class="sr-section-title">About San Roque High School</div>
            <div class="sr-section-desc">Committed to excellence in education since 2001.</div>
            <div class="sr-cards">
                <div class="sr-card">
                    <div class="sr-card-icon">
                        <i class="fas fa-graduation-cap" style="color: var(--blue);"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To provide quality education and develop well-rounded individuals who are prepared for higher education and life's challenges.</p>
                </div>
                <div class="sr-card">
                    <div class="sr-card-icon">
                        <i class="fas fa-eye" style="color: var(--blue);"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>To be a leading educational institution that produces globally competitive and morally upright graduates.</p>
                </div>
                <div class="sr-card">
                    <div class="sr-card-icon">
                        <i class="fas fa-star" style="color: var(--blue);"></i>
                    </div>
                    <h3>Our Values</h3>
                    <p>Excellence, integrity, service, and commitment to the holistic development of every student.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How to Apply Section -->
    <div style="background: var(--bg-page);">
        <div class="sr-section" style="padding-top: 0;">
            <div class="sr-section-label">Enrollment</div>
            <div class="sr-section-title">How to Apply</div>
            <div class="sr-section-desc">Complete your application in four simple steps.</div>
            <div class="sr-steps">
                <div class="sr-step">
                    <div class="sr-step-num">1</div>
                    <h4>Fill out the form</h4>
                    <p>Complete the online admission form with your personal and academic details.</p>
                </div>
                <div class="sr-step">
                    <div class="sr-step-num">2</div>
                    <h4>Submit documents</h4>
                    <p>Upload required documents such as your birth certificate and report card.</p>
                </div>
                <div class="sr-step">
                    <div class="sr-step-num">3</div>
                    <h4>Await review</h4>
                    <p>The registrar will review your application and notify you of the outcome.</p>
                </div>
                <div class="sr-step">
                    <div class="sr-step-num">4</div>
                    <h4>Complete enrollment</h4>
                    <p>Once accepted, finalize your enrollment by reporting to the registrar's office.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="sr-divider"><hr></div>

    <!-- Contact Section -->
    <div id="contact" style="background: var(--bg-page);">
        <div class="sr-section">
            <div class="sr-section-label">Contact</div>
            <div class="sr-section-title">Get in Touch</div>
            <div class="sr-section-desc">Reach us through any of the following channels.</div>
            <div class="sr-contact-grid">
                <div class="sr-contact-card">
                    <div class="sr-contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4>Address</h4>
                    <p>San Roque, Bato,<br>Camarines Sur</p>
                </div>
                <div class="sr-contact-card">
                    <div class="sr-contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Email</h4>
                    <a href="mailto:sanroquehighschool2001@gmail.com">sanroquehighschool2001@gmail.com</a>
                </div>
                <div class="sr-contact-card">
                    <div class="sr-contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4>Phone</h4>
                    <p>Contact via email or Facebook</p>
                </div>
                <div class="sr-contact-card">
                    <div class="sr-contact-icon">
                        <i class="fab fa-facebook"></i>
                    </div>
                    <h4>Facebook</h4>
                    <a href="https://www.facebook.com/SanRoqueHS2001" onclick="openFacebookApp(event)" target="_blank" rel="noopener noreferrer">
                        San Roque High School
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="sr-footer">
        <p>&copy; {{ date('Y') }} San Roque High School. All Rights Reserved.</p>
        <div class="sr-footer-links">
            <a href="{{ route('admission.create.public') }}">Admission</a>
            <a href="{{ route('admission.status') }}">Check Status</a>
            <a href="#contact" onclick="event.preventDefault(); document.getElementById('contact').scrollIntoView({behavior:'smooth'});">Contact</a>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    @include('components.info-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-open info modal on page load
            const infoModalEl = document.getElementById('infoModal');
            if (infoModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const infoModal = new bootstrap.Modal(infoModalEl);
                infoModal.show();
            }

            // Hamburger toggle
            const hamburger = document.getElementById('hamburger');
            const mobileMenu = document.getElementById('mobileMenu');

            hamburger.addEventListener('click', function () {
                const isOpen = mobileMenu.classList.toggle('open');
                hamburger.classList.toggle('open', isOpen);
                hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            // Close on outside click
            document.addEventListener('click', function (e) {
                if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.remove('open');
                    hamburger.classList.remove('open');
                    hamburger.setAttribute('aria-expanded', 'false');
                }
            });

            // Close on Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    mobileMenu.classList.remove('open');
                    hamburger.classList.remove('open');
                    hamburger.setAttribute('aria-expanded', 'false');
                }
            });

            // Close menu + smooth scroll for About/Contact in mobile menu
            const mobileAbout = document.getElementById('mobileAbout');
            const mobileContact = document.getElementById('mobileContact');

            if (mobileAbout) {
                mobileAbout.addEventListener('click', function (e) {
                    e.preventDefault();
                    mobileMenu.classList.remove('open');
                    hamburger.classList.remove('open');
                    document.getElementById('about').scrollIntoView({ behavior: 'smooth' });
                });
            }

            if (mobileContact) {
                mobileContact.addEventListener('click', function (e) {
                    e.preventDefault();
                    mobileMenu.classList.remove('open');
                    hamburger.classList.remove('open');
                    document.getElementById('contact').scrollIntoView({ behavior: 'smooth' });
                });
            }

            // Close menu on link click (for nav links inside mobile menu)
            mobileMenu.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    mobileMenu.classList.remove('open');
                    hamburger.classList.remove('open');
                    hamburger.setAttribute('aria-expanded', 'false');
                });
            });

            // Close on resize back to desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth > 900) {
                    mobileMenu.classList.remove('open');
                    hamburger.classList.remove('open');
                    hamburger.setAttribute('aria-expanded', 'false');
                }
            });
        });

        // Facebook app deep link for mobile
        function openFacebookApp(event) {
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
            const isAndroid = /Android/i.test(navigator.userAgent);

            if (isMobile) {
                event.preventDefault();
                const facebookWebUrl = 'https://www.facebook.com/SanRoqueHS2001';
                let facebookAppUrl;

                if (isIOS) {
                    facebookAppUrl = 'fb://profile/SanRoqueHS2001';
                } else if (isAndroid) {
                    facebookAppUrl = 'intent://www.facebook.com/SanRoqueHS2001#Intent;package=com.facebook.katana;scheme=https;end';
                } else {
                    facebookAppUrl = 'fb://page/SanRoqueHS2001';
                }

                window.location.href = facebookAppUrl;
                setTimeout(function () {
                    window.location.href = facebookWebUrl;
                }, 500);
            }
        }
    </script>
</body>
</html>