<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\TwoFactorCode;

class ForgotPasswordController extends Controller
{
    // ... (Keep existing methods: showLinkRequestForm, sendResetLinkEmail) ...

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that e-mail address.']);
        }

        if ($user->role === 'admin') {
            return back()->withErrors(['email' => 'Admins cannot reset passwords via email. Please contact support.']);
        }

        $user->generateTwoFactorCode();

        try {
            Mail::to($user->email)->send(new TwoFactorCode($user->two_factor_code));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Network error. Could not send OTP.']);
        }

        return redirect()->route('password.otp', ['email' => $user->email]);
    }

    public function showOtpForm(Request $request)
    {
        return view('auth.passwords.otp', ['email' => $request->email]);
    }

    // --- ADDED RESEND METHOD ---
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->generateTwoFactorCode();
            try {
                Mail::to($user->email)->send(new TwoFactorCode($user->two_factor_code));
                return response()->json(['message' => 'New code sent!']);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Could not send email.'], 500);
            }
        }

        return response()->json(['message' => 'User not found.'], 404);
    }
    // ---------------------------

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'two_factor_code' => 'required|integer',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($request->two_factor_code == $user->two_factor_code) {
            
            if ($user->two_factor_expires_at && $user->two_factor_expires_at->lt(now())) {
                $user->resetTwoFactorCode();
                return back()->withErrors(['two_factor_code' => 'The code has expired. Please request a new one.']);
            }

            $user->resetTwoFactorCode();
            $token = Password::createToken($user);
            
            return redirect()->route('password.reset', [
                'token' => $token, 
                'email' => $user->email
            ]);
        }

        return back()->withErrors(['two_factor_code' => 'The code you entered is incorrect.']);
    }
}