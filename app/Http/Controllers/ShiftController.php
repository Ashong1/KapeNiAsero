<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
     * Display a listing of shifts (History).
     */
    public function index()
    {
        // Only Admin should see the history list here
        if (Auth::user()->role !== 'admin') {
             // If employee tries to view history, redirect based on shift status
             $activeShift = Shift::where('user_id', Auth::id())->whereNull('ended_at')->first();
             return $activeShift ? redirect()->route('orders.index') : redirect()->route('shifts.create');
        }

        $shifts = Shift::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new shift (Clock In).
     */
    public function create()
    {
        // Prevent double shift start
        $activeShift = Shift::where('user_id', Auth::id())->whereNull('ended_at')->first();
        
        // If they already have a shift, send them to work (POS)
        if ($activeShift) {
            return redirect()->route('orders.index');
        }

        // If they are Admin, they don't need to start a shift usually, but let's allow it or redirect
        if (Auth::user()->role === 'admin') {
             return redirect()->route('home')->with('info', 'Admins do not need to start shifts.');
        }

        return view('shifts.create');
    }

    /**
     * Store a newly created shift in storage.
     */
    public function store(Request $request)
    {
        // Validate input with a custom message for missing amount
        $request->validate([
            'start_cash' => 'required|numeric|min:0',
        ], [
            'start_cash.required' => 'You must input a starting amount to begin the shift.',
        ]);

        Shift::create([
            'user_id' => Auth::id(),
            'start_cash' => $request->start_cash,
            'started_at' => now(),
        ]);

        return redirect()->route('orders.index')->with('success', 'Register open. Shift started.');
    }

    /**
     * Show the form for editing (Ending) the specified shift.
     */
    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        
        // Security: Ensure users can only close their OWN shift unless they are admin
        if (Auth::user()->role !== 'admin' && $shift->user_id !== Auth::id()) {
            return redirect()->route('orders.index');
        }

        // Calculate Expected Cash
        $cashSales = Order::where('user_id', $shift->user_id)
                          ->where('payment_mode', 'cash') 
                          ->where('status', 'completed')
                          ->whereBetween('created_at', [$shift->started_at, now()])
                          ->sum('total_price');

        $expectedCash = $shift->start_cash + $cashSales;

        return view('shifts.edit', compact('shift', 'cashSales', 'expectedCash'));
    }

    /**
     * Update the specified shift (Clock Out).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'end_cash' => 'required|numeric|min:0',
        ]);

        $shift = Shift::findOrFail($id);
        
        // Recalculate everything to be safe
        $cashSales = Order::where('user_id', $shift->user_id)
                          ->where('payment_mode', 'cash')
                          ->where('status', 'completed')
                          ->whereBetween('created_at', [$shift->started_at, now()])
                          ->sum('total_price');

        $shift->end_cash = $request->end_cash;
        $shift->expected_cash = $shift->start_cash + $cashSales;
        $shift->ended_at = now();
        $shift->save();

        Auth::logout(); // Logout after ending shift

        return redirect()->route('login')->with('success', 'Shift closed successfully.');
    }
    
    // --- EMPLOYEE: LOGOUT HANDLER ---
    public function handleLogout(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            Auth::logout();
            return redirect('/login');
        }

        $activeShift = Shift::where('user_id', Auth::id())->whereNull('ended_at')->first();

        if ($activeShift) {
            return redirect()->route('shifts.edit', $activeShift->id)
                             ->with('error', 'Please close your register before logging out.');
        }

        Auth::logout();
        return redirect('/login');
    }
}