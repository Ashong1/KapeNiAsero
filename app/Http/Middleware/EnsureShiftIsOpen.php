<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Shift;

class EnsureShiftIsOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Admins bypass this check completely
        if (!$user || $user->role === 'admin') {
            return $next($request);
        }

        // 2. Check if the user has an active shift (Open Register)
        $activeShift = Shift::where('user_id', $user->id)
                            ->whereNull('ended_at')
                            ->exists();

        // 3. Define routes that are allowed even without a shift
        $excludedRoutes = [
            'shifts.create', // The page to open register
            'shifts.store',  // The action to submit the open register form
            'logout.action', // The custom logout button
            'logout',        // Standard logout
        ];

        // 4. If no active shift AND the user is trying to go somewhere else (like /products)
        if (!$activeShift && !in_array($request->route()->getName(), $excludedRoutes)) {
            // Redirect to Open Register page with a specific error message
            return redirect()->route('shifts.create')
                             ->with('error', 'You must open the register before accessing the system.');
        }

        return $next($request);
    }
}