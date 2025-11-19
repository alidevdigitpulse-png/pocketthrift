<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRegionUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Allow if user is regular admin (role 1)
            if ($user->role == 1) {
                return $next($request);
            }
            
            // Allow if user has assigned regions (regardless of role)
            if ($user->assigned_regions) {
                return $next($request);
            }
            
            // For users without assigned regions, redirect based on their role
            if ($user->role == 2) {
                return redirect()->route('user.dashboard');
            } else {
                return redirect()->route('login');
            }
        }

        return redirect()->route('login');
    }
}