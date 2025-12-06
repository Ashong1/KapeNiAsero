<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse; 
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Models\Shift; 

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // --- CUSTOM LOGIC STARTS HERE ---

    /**
     * Determine the maximum number of attempts to allow.
     * Returns 3 for Admins, 5 for others.
     */
    public function maxAttempts()
    {
        // Get the email from the request to check the user role
        $email = request()->input($this->username());
        $user = User::where('email', $email)->first();

        // Check if user exists and is an Admin
        if ($user && $user->role === 'admin') {
            return 3; // Lock after 3 failed tries
        }

        return 5; // Default for employees
    }

    /**
     * Determine how many minutes to lock the user out.
     * Admins get locked for 30 minutes. Others for 1 minute.
     */
    public function decayMinutes()
    {
        $email = request()->input($this->username());
        $user = User::where('email', $email)->first();

        if ($user && $user->role === 'admin') {
            return 30; // Lockout duration in minutes
        }

        return 1; // Default duration
    }

    /**
     * Custom response for failed login to show remaining attempts.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Get the rate limiter key
        $key = $this->throttleKey($request);
        
        // Get max attempts
        $maxAttempts = $this->maxAttempts();
        
        // Calculate remaining attempts
        $remaining = $this->limiter()->retriesLeft($key, $maxAttempts);

        throw ValidationException::withMessages([
            $this->username() => [
                trans('auth.failed'), 
                "Warning: You have {$remaining} attempt(s) remaining."
            ],
        ]);
    }

    /**
     * Custom Redirect Logic
     * Handles where the user goes immediately after logging in.
     */
    public function redirectTo()
    {
        $user = auth()->user();

        // 1. Admins go to Dashboard
        if ($user->role === 'admin') {
            return '/home';
        }

        // 2. Check if Employee has an active shift
        // We check this here to avoid the "Middleware Error" appearing on /home
        $hasShift = Shift::where('user_id', $user->id)
                        ->whereNull('ended_at')
                        ->exists();

        // 3. If NO active shift, send them DIRECTLY to Start Shift page
        if (!$hasShift) {
            return route('shifts.create');
        }

        // 4. If they DO have a shift, proceed to Home/POS
        return '/home'; 
    }

    /**
     * Overriding the default logout method.
     * This prevents employees from logging out if they have an open register.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        // 1. Check if user is an employee (Admins can always logout)
        if ($user && $user->role !== 'admin') {
            
            // 2. Check for an active shift (open register)
            $activeShift = Shift::where('user_id', $user->id)
                                ->whereNull('ended_at') // Shift is still open
                                ->first();

            if ($activeShift) {
                // 3. STOP LOGOUT: Redirect to the "End Shift" page with an error
                return redirect()->route('shifts.edit', $activeShift->id)
                                 ->with('error', 'You must close your register before logging out.');
            }
        }

        // 4. PROCEED: Normal Logout logic (if no open shift or is admin)
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}