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
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users.
    |
    */

    /**
     * 1. Display the form to request a password reset link.
     * (This fixes the "Method not found" error)
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * 2. Handle the "Send Reset Link" form submission.
     * Instead of sending a link, we generate an OTP and send it.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        // If user doesn't exist, return error (Standard Laravel behavior)
        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that e-mail address.']);
        }

        // Generate OTP using the User model function
        $user->generateTwoFactorCode();

        // Send the OTP Email
        try {
            Mail::to($user->email)->send(new TwoFactorCode($user->two_factor_code));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Network error. Could not send OTP.']);
        }

        // Redirect to the OTP entry page with the email
        return redirect()->route('password.otp', ['email' => $user->email]);
    }

    /**
     * 3. Show the OTP Entry Form.
     */
    public function showOtpForm(Request $request)
    {
        return view('auth.passwords.otp', ['email' => $request->email]);
    }

    /**
     * 4. Verify the OTP.
     * If valid, generate a token and redirect to the actual Password Reset form.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'two_factor_code' => 'required|integer',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if code matches
        if ($request->two_factor_code == $user->two_factor_code) {
            
            // Check if code is expired
            if ($user->two_factor_expires_at && $user->two_factor_expires_at->lt(now())) {
                $user->resetTwoFactorCode();
                return back()->withErrors(['two_factor_code' => 'The code has expired. Please request a new one.']);
            }

            // Code is valid: Clear it
            $user->resetTwoFactorCode();
            
            // Generate a valid Password Reset Token for the user
            // This allows us to use the standard "Reset Password" form securely
            $token = Password::createToken($user);
            
            // Redirect to the password reset form with the token
            return redirect()->route('password.reset', [
                'token' => $token, 
                'email' => $user->email
            ]);
        }

        return back()->withErrors(['two_factor_code' => 'The code you entered is incorrect.']);
    }
}