<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in AND is an admin
        if (auth()->check() && auth()->user()->role == 'admin') {
            return $next($request);
        }

        // If not, send them back to the menu with an error
        return redirect('/products')->with('error', 'You do not have admin access.');
    }
}
