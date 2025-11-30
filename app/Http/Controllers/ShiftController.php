<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    // Show form to open register
    public function create()
    {
        $activeShift = Shift::where('user_id', Auth::id())->whereNull('ended_at')->first();
        if ($activeShift) {
            return redirect()->route('products.index');
        }
        return view('shifts.create');
    }

    // Start the shift (Clock In)
    public function store(Request $request)
    {
        $request->validate([
            'start_cash' => 'required|numeric|min:0',
        ]);

        Shift::create([
            'user_id' => Auth::id(),
            'start_cash' => $request->start_cash,
            'started_at' => now(),
        ]);

        // Redirect directly to POS after opening register
        return redirect()->route('products.index')->with('success', 'Shift started.');
    }

    // Show form to close register (End Shift)
    public function edit(Shift $shift)
    {
        if ($shift->user_id !== Auth::id() || $shift->ended_at) {
            abort(403);
        }

        // LIVE SALES CALCULATION
        $cashSales = Order::where('user_id', Auth::id())
            ->where('created_at', '>=', $shift->started_at)
            ->where('payment_mode', 'cash')
            ->where('status', '!=', 'voided')
            ->sum('total_price');

        $expectedCash = $shift->start_cash + $cashSales;

        return view('shifts.edit', compact('shift', 'expectedCash', 'cashSales'));
    }

    // Close the shift and LOGOUT
    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'end_cash' => 'required|numeric|min:0',
        ]);

        $cashSales = Order::where('user_id', Auth::id())
            ->where('created_at', '>=', $shift->started_at)
            ->where('payment_mode', 'cash')
            ->where('status', '!=', 'voided')
            ->sum('total_price');
        
        $expectedCash = $shift->start_cash + $cashSales;

        $shift->update([
            'end_cash' => $request->end_cash,
            'expected_cash' => $expectedCash,
            'ended_at' => now(),
        ]);

        // FORCE LOGOUT AFTER CLOSING SHIFT
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Shift ended. Sales recorded: ₱' . number_format($cashSales, 2));
    }

    // NEW: Handle Logout Button Click
    public function handleLogout(Request $request)
    {
        // If Admin, just logout normally
        if (Auth::user()->role === 'admin') {
            Auth::logout();
            return redirect('/login');
        }

        // If Employee, check for open shift
        $activeShift = Shift::where('user_id', Auth::id())->whereNull('ended_at')->first();

        if ($activeShift) {
            // Redirect to Close Register screen instead of logging out
            // FIX IS HERE: Changed 'shifts.close' to 'shifts.edit'
            return redirect()->route('shifts.edit', $activeShift->id)
                             ->with('error', 'Please close your register before logging out.');
        }

        // If no shift found (rare), just logout
        Auth::logout();
        return redirect('/login');
    }
}