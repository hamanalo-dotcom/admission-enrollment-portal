<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentNotificationController extends Controller
{
    public function index()
    {
        // Fetch notifications for the authenticated student
        // $notifications = auth()->user()->notifications;

        return view('student.notifications.index');
    }

    public function markAllAsRead()
    {
        $user = auth()->user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
        return redirect()->route('student.notifications.index')->with('success', 'All notifications marked as read.');
    }
}
