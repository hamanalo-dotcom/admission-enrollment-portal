<div class="header-container">
    @if (!request()->routeIs('admission.create.public'))
        <button class="burger-btn" id="burgerBtn" style="display: none;">
            <i class="fas fa-bars"></i>
        </button>
    @endif
    <div style="flex: 1;"></div>
    @auth
    @php
        $role = auth()->user()->role ?? null;
    @endphp
    @if(in_array($role, ['student', 'teacher']))
        @php
            $user = auth()->user();
            $unreadCount = $user->unreadNotifications->count();
        @endphp
        <div class="header-actions">
            <!-- Notifications Icon -->
            <a href="{{ $role === 'student' ? route('student.notifications.index') : route('teacher.notifications.index') }}" class="header-icon-btn" title="Notifications">
                <i class="fas fa-bell"></i>
                @if($unreadCount > 0)
                    <span class="header-notification-badge">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </a>
            @if($role === 'student')
                @php
                    $initials = strtoupper(substr($user->firstname ?? '', 0, 1) . substr($user->lastname ?? '', 0, 1));
                @endphp
                <!-- Student Profile Picture -->
                <div class="header-avatar">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture">
                    @else
                        {{ $initials }}
                    @endif
                </div>
            @endif
            <!-- Logout Icon -->
            <form method="POST" action="{{ route('logout') }}" style="margin: 0; display: inline;">
                @csrf
                <button type="submit" class="header-icon-btn header-logout-btn" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    @endif
    @endauth
</div>

<style>
    .burger-btn {
        background: none;
        border: none;
        font-size: 22px;
        color: var(--ds-secondary);
        cursor: pointer;
        padding: 8px 12px;
        border-radius: var(--ds-radius);
        transition: var(--ds-transition);
    }
    .burger-btn:hover {
        background: rgba(var(--ds-secondary-rgb), 0.08);
    }

    .header-icon-btn {
        position: relative;
        color: var(--ds-secondary);
        font-size: 18px;
        text-decoration: none;
        padding: 8px;
        border-radius: 50%;
        transition: var(--ds-transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        cursor: pointer;
    }
    .header-icon-btn:hover {
        background: rgba(var(--ds-secondary-rgb), 0.08);
        color: var(--ds-primary);
    }
    .header-logout-btn:hover {
        background: rgba(229, 62, 62, 0.08);
        color: var(--ds-danger);
    }

    .header-notification-badge {
        position: absolute;
        top: 0;
        right: 0;
        background-color: var(--ds-danger);
        color: #fff;
        border-radius: var(--ds-radius-pill);
        padding: 2px 6px;
        font-size: 10px;
        font-weight: 700;
        min-width: 18px;
        text-align: center;
        border: 2px solid var(--ds-bg-card);
        line-height: 1.2;
    }

    .header-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid var(--ds-accent);
        background: linear-gradient(135deg, var(--ds-accent) 0%, var(--ds-accent-light) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
    }
    .header-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .header-container {
            margin-left: 0 !important;
        }
        .burger-btn {
            display: block !important;
        }
        .sidebar-overlay.active {
            display: block !important;
        }
    }
</style>
