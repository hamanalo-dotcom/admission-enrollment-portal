<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherNotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(20);
        
        // Mark notifications as read when viewing
        auth()->user()->unreadNotifications->markAsRead();

        return view('teacher.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}

