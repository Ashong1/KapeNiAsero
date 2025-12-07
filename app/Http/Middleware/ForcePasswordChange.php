<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user is logged in AND must change password
        if ($user && $user->must_change_password) {
            
            // Allow them to access the change password routes and logout
            if ($request->routeIs('password.change') || 
                $request->routeIs('password.change.update') || // FIX: Added new route name
                $request->routeIs('logout')) {
                return $next($request);
            }

            // Otherwise, redirect them to the change password page
            return redirect()->route('password.change')
                ->with('warning', 'You must change your password before continuing.');
        }

        return $next($request);
    }
}