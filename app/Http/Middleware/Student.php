<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Student
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (Auth::check() && Auth::user()->role == 'student') {
      return $next($request);
    }

    if (!Auth::check()) {
      return redirect()->guest(route('login'));
    }

    $dashboards = [
      'admin' => '/admin-dashboard',
      'registrar' => '/registrar-dashboard',
      'teacher' => '/teacher-dashboard',
      'student' => '/student-dashboard',
    ];
    $role = Auth::user()->role;
    if (!isset($dashboards[$role])) {
      return redirect()->route('error');
    }

    return redirect($dashboards[$role])->with('error',
      'That link is for Student accounts. You are currently signed in as ' . ucfirst($role) . '. Please log out and sign in with the correct account to open it.');
  }
}
