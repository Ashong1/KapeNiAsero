<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;                    // Import Request
use Illuminate\Validation\ValidationException;  // Import ValidationException
use App\Models\User;                            // Import User Model

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
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

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
     * This overrides the default method in the AuthenticatesUsers trait.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Get the rate limiter key
        $key = $this->throttleKey($request);
        
        // Get max attempts (using the dynamic function above)
        $maxAttempts = $this->maxAttempts();
        
        // Calculate remaining attempts
        // 'retriesLeft' automatically calculates (Max - Current Hits)
        $remaining = $this->limiter()->retriesLeft($key, $maxAttempts);

        throw ValidationException::withMessages([
            $this->username() => [
                trans('auth.failed'), // "These credentials do not match our records."
                "Warning: You have {$remaining} attempt(s) remaining."
            ],
        ]);
    }
    public function redirectTo()
    {
        // Admins go to Dashboard
        if (auth()->user()->role === 'admin') {
            return '/home';
        }

        // Employees go strictly to the POS/Orders page
        return '/orders';
    }
}