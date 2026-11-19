<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();

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

        return $next($request);
    }
}
