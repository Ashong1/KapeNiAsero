<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    /**
     * Show the change password form.
     */
    public function show()
    {
        return view('auth.change-password');
    }

    /**
     * Update the password.
     */
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        $user = Auth::user();
        
        // FIX: Use forceFill to ensure must_change_password saves correctly
        $user->forceFill([
            'password' => Hash::make($request->password),
            'must_change_password' => 0, 
        ])->save();

        // Refresh the user session to prevent logout issues
        Auth::setUser($user);

        // Redirect based on role
        $route = $user->role === 'admin' ? 'home' : 'orders.index';

        return redirect()->route($route)->with('success', 'Password updated successfully!');
    }
}