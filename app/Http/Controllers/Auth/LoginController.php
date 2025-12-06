<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;                    
use Illuminate\Validation\ValidationException;  
use App\Models\User;                            
use App\Models\Shift; // Import Shift model

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
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
     */
    public function maxAttempts()
    {
        $email = request()->input($this->username());
        $user = User::where('email', $email)->first();

        if ($user && $user->role === 'admin') {
            return 3; 
        }

        return 5; 
    }

    /**
     * Determine how many minutes to lock the user out.
     */
    public function decayMinutes()
    {
        $email = request()->input($this->username());
        $user = User::where('email', $email)->first();

        if ($user && $user->role === 'admin') {
            return 30; 
        }

        return 1; 
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $key = $this->throttleKey($request);
        $maxAttempts = $this->maxAttempts();
        $remaining = $this->limiter()->retriesLeft($key, $maxAttempts);

        throw ValidationException::withMessages([
            $this->username() => [
                trans('auth.failed'), 
                "Warning: You have {$remaining} attempt(s) remaining."
            ],
        ]);
    }
    
    /**
     * The user has been authenticated.
     * We override the redirect path logic here.
     */
    public function redirectTo()
    {
        $user = auth()->user();

        // 1. Admins go to Dashboard
        if ($user->role === 'admin') {
            return '/home';
        }

        // 2. Employees: Check if they have an active shift
        $activeShift = Shift::where('user_id', $user->id)
                            ->whereNull('ended_at')
                            ->exists();
        
        // 3. If NO active shift, send them directly to Start Shift page
        // This prevents the Middleware from flashing an error message on /home
        if (!$activeShift) {
            return route('shifts.create');
        }

        // 4. If they DO have a shift, go to /home (or POS)
        return '/home'; 
    }
}