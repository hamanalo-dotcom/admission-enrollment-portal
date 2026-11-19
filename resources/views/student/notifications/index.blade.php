@extends('layout.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            background-color: #f5f7fa;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        .main-content {
            padding: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header-section h1 {
            font-size: 32px;
            color: #2c5aa0;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 700;
        }

        /* Notifications Container */
        .notifications-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            margin-bottom: 15px;
            background: white;
            border-radius: 10px;
            border-left: 5px solid #2c5aa0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateX(5px);
        }

        .notification-dot {
            width: 12px;
            height: 12px;
            background-color: #e74c3c;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 5px;
        }

        .notification-dot.read {
            background-color: #d4e5f3;
        }

        .notification-content {
            flex: 1;
        }

        .notification-message {
            font-size: 15px;
            color: #555;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .notification-time {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
        }

        .notification-action {
            display: inline-block;
            padding: 8px 16px;
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .notification-action:hover {
            background: linear-gradient(135deg, #1e4170 0%, #3e5370 100%);
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 40px;
            color: #999;
        }

        .empty-state i {
            font-size: 64px;
            color: #d4e5f3;
            margin-bottom: 20px;
        }

        .empty-state p {
            font-size: 16px;
            color: #2c5aa0;
            font-weight: 600;
        }

        .notification-type {
            display: inline-block;
            padding: 4px 10px;
            background-color: #e8f4f8;
            color: #2c5aa0;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 24px;
            }

            .notification-item {
                padding: 15px;
            }

            .main-content {
                padding: 15px;
            }
        }
    </style>

    <div class="main-content">
        <!-- Header Section -->
        <div class="header-section">
            <div>
                <h1>
                    🔔 Notifications
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </h1>
            </div>
            <div>
                @if(auth()->user()->notifications->count() > 0)
                    <form action="{{ route('student.notifications.markAllRead') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="notification-action">Mark all as read</button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Notifications Container -->
        <div class="notifications-container">
            @forelse(auth()->user()->notifications as $notification)
                <div class="notification-item">
                    <div class="notification-content">
                        <div class="notification-type">
                            @if(isset($notification->data['type']))
                                {{ $notification->data['type'] }}
                            @else
                                System
                            @endif
                        </div>

                        <div class="notification-message">
                            {{ $notification->data['message'] ?? 'New notification' }}
                        </div>

                        <div class="notification-time">
                            📅 {{ $notification->created_at->format('M d, Y \a\t h:i A') }}
                        </div>

                        @if(isset($notification->data['status']) && $notification->data['status'] === 'approved')
                            <a href="{{ route('student.enrollment.create') }}" class="notification-action">
                                ✓ Proceed to Enrollment
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-bell-slash"></i>
                    <p>No notifications yet</p>
                    <p style="font-size: 13px; color: #999; margin-top: 8px; font-weight: 400;">You're all caught up! Check back later for updates.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection