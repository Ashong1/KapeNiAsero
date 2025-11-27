<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCode; 

class TwoFactorController extends Controller
{
    public function index() 
    {
        return view('auth.twoFactor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        $user = auth()->user();

        if($request->input('two_factor_code') == $user->two_factor_code)
        {
            $user->resetTwoFactorCode();
            
            // --- FIX IS HERE ---
            // OLD: return redirect()->route('products.index'); 
            // NEW: Redirect to 'home' so HomeController can check if Admin or Employee
            return redirect()->route('home'); 
        }

        return redirect()->back()->withErrors(['two_factor_code' => 'The code you entered is incorrect.']);
    }

    public function resend()
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        
        try {
            Mail::to($user->email)->send(new TwoFactorCode($user->two_factor_code));
        } catch (\Exception $e) {
            \Log::info("2FA CODE RESENT FOR {$user->email}: {$user->two_factor_code}");
            return redirect()->back()->withErrors(['msg' => 'Email failed. Check laravel.log for code.']);
        }
        
        return redirect()->back()->with('message', 'The code has been sent again.');
    }
}