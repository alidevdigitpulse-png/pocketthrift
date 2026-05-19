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
            
            // Allow if user has role 0, 1, or 2, OR has Spatie roles
            if (in_array($user->role, [0, 1, 2]) || $user->hasRole('admin') || $user->hasRole('super admin')) {
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