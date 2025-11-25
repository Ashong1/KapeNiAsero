<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCode;

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
    protected $redirectTo = '/home';

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
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        $user->generateTwoFactorCode();
        
        // SEND REAL EMAIL
        try {
            Mail::to($user->email)->send(new TwoFactorCode($user->two_factor_code));
        } catch (\Exception $e) {
            \Log::error("Mail Error: " . $e->getMessage());
            // Continue anyway so the user isn't stuck if internet is slow
        }

        return redirect()->route('verify.index');
    }
}
