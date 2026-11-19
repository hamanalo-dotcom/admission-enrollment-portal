<div class="header-container" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: fixed; top: 0; left: 0; right: 0; z-index: 999;">
    @if (!request()->routeIs('admission.create.public'))
        <button class="burger-btn" id="burgerBtn" style="display: none; background: none; border: none; font-size: 24px; color: #2c5aa0; cursor: pointer; padding: 5px 10px;">
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
        <div style="display: flex; align-items: center; gap: 15px;">
            <!-- Notifications Icon -->
            <a href="{{ $role === 'student' ? route('student.notifications.index') : route('teacher.notifications.index') }}" style="position: relative; color: #2c5aa0; font-size: 20px; text-decoration: none; padding: 8px; border-radius: 50%; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='rgba(44, 90, 160, 0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                <i class="fas fa-bell"></i>
                @if($unreadCount > 0)
                    <span style="position: absolute; top: 0; right: 0; background-color: #e74c3c; color: white; border-radius: 12px; padding: 2px 6px; font-size: 11px; font-weight: 700; min-width: 18px; text-align: center; border: 2px solid white; line-height: 1.2;">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </a>
            @if($role === 'student')
                @php
                    $initials = strtoupper(substr($user->firstname ?? '', 0, 1) . substr($user->lastname ?? '', 0, 1));
                @endphp
                <!-- Student Profile Picture -->
                <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: 2px solid #2c5aa0; background: linear-gradient(135deg, #4a8fd8 0%, #7fb3ed 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        {{ $initials }}
                    @endif
                </div>
            @endif
            <!-- Logout Icon -->
            <form method="POST" action="{{ route('logout') }}" style="margin: 0; display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #2c5aa0; font-size: 20px; cursor: pointer; padding: 8px; border-radius: 50%; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='rgba(255, 50, 50, 0.1)'; this.style.color='#dc3545'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#2c5aa0'">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    @endif
    @endauth
</div>

<style>
    @media (max-width: 768px) {
        .header-container {
            margin-left: 0 !important;
        }
        
        .burger-btn {
            display: block !important;
        }
        
        /* Overlay when sidebar is open - only on mobile */
        .sidebar-overlay.active {
            display: block !important;
        }
    }
</style>

