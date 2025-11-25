<?php

namespace App\Http\Controllers; // <--- This line is CRITICAL

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCode; 

class TwoFactorController extends Controller
{
    // 1. Show the "Enter Code" Form
    public function index() 
    {
        return view('auth.twoFactor');
    }

    // 2. Check if the code is correct
    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        $user = auth()->user();

        // Compare User Input vs Database Code
        if($request->input('two_factor_code') == $user->two_factor_code)
        {
            $user->resetTwoFactorCode(); // Clear code so they don't get asked again
            return redirect()->route('products.index'); // Send to POS
        }

        // If wrong, go back with error
        return redirect()->back()->withErrors(['two_factor_code' => 'The code you entered is incorrect.']);
    }

    // 3. Resend the Code via Email
    public function resend()
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        
        // Attempt to send email
        try {
            Mail::to($user->email)->send(new TwoFactorCode($user->two_factor_code));
        } catch (\Exception $e) {
            // If email fails (no internet), log it so you can still login
            \Log::info("2FA CODE RESENT FOR {$user->email}: {$user->two_factor_code}");
            return redirect()->back()->withErrors(['msg' => 'Email failed. Check laravel.log for code.']);
        }
        
        return redirect()->back()->with('message', 'The code has been sent again.');
    }
}