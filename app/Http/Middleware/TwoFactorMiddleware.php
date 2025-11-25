<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if user is logged in AND has a code waiting
        if (auth()->check() && $user->two_factor_code) {
            
            // SECURITY CHECK: Ensure the expiry date exists and check if expired
            if ($user->two_factor_expires_at && $user->two_factor_expires_at->lt(now())) {
                
                $user->resetTwoFactorCode();
                
                Auth::logout(); 
                
                return redirect()->route('login')
                    ->with('error', 'The two factor code has expired. Please login again.');
            }

            // If they are NOT on the verify page, send them there
            if (!$request->is('verify*')) {
                return redirect()->route('verify.index');
            }
        }

        return $next($request);
    }
}