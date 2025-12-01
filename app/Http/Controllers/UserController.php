<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Get all users, ordered by newest first
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,employee',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'New user account created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     * RESTRICTION: Admins cannot access the edit page of OTHER Admins.
     */
    public function edit(User $user)
    {
        // STRICT CHECK: If target is Admin AND it is not ME, deny access.
        if ($user->role === 'admin' && $user->id !== Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Access Denied: You cannot edit another Administrator account.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     * RESTRICTION: Admins cannot update OTHER Admins.
     */
    public function update(Request $request, User $user)
    {
        // 1. STRICT CHECK: If target is Admin AND it is not ME, deny access.
        if ($user->role === 'admin' && $user->id !== Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Access Denied: You cannot update another Administrator account.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,employee',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // 2. Prevent self-demotion (You cannot remove your own Admin status)
        if ($user->id === Auth::id() && $request->role !== 'admin') {
             return redirect()->back()->with('error', 'Safety Restriction: You cannot remove your own Administrator privileges.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // 3. Only update password if provided AND it is the user updating themselves
        // Admins cannot change the password of other users (Employees) here.
        if ($request->filled('password') && $user->id === Auth::id()) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User account updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     * RESTRICTION: Admins cannot delete OTHER Admins.
     */
    public function destroy(User $user)
    {
        // 1. Prevent deleting self
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account while logged in.');
        }

        // 2. Prevent deleting OTHER admins
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Access Denied: You cannot delete another Administrator account.');
        }

        // 3. Try to delete, handle database errors (Foreign Keys)
        try {
            $user->delete();
        } catch (QueryException $e) {
            // Error 23000: Integrity Constraint Violation (Foreign Key fails)
            if ($e->getCode() === "23000") {
                return redirect()->back()
                    ->with('error', 'Cannot delete user: This user has associated records (Orders, Activity Logs, etc.). Please disable the account by changing the password instead.');
            }
            
            return redirect()->back()->with('error', 'Database Error: ' . $e->getMessage());
        }

        return redirect()->route('users.index')
            ->with('success', 'User account deleted successfully.');
    }
}