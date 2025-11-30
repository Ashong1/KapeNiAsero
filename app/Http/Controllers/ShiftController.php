<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    // --- ADMIN: SHIFT HISTORY & VARIANCE REPORT ---
    public function index()
    {
        // Only Admin should see the history
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Get completed shifts (where ended_at is not null), latest first
        $shifts = Shift::with('user')
                       ->whereNotNull('ended_at')
                       ->latest('ended_at')
                       ->paginate(15);

        return view('shifts.index', compact('shifts'));
    }

    // --- EMPLOYEE: OPEN REGISTER ---
    public function create()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('shifts.index')->with('error', 'Admins view history here. You do not open registers.');
        }

        $activeShift = Shift::where('user_id', Auth::id())->whereNull('ended_at')->first();
        if ($activeShift) {
            return redirect()->route('products.index');
        }
        return view('shifts.create');
    }

    // --- EMPLOYEE: START SHIFT ---
    public function store(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            abort(403);
        }

        $request->validate(['start_cash' => 'required|numeric|min:0']);

        Shift::create([
            'user_id' => Auth::id(),
            'start_cash' => $request->start_cash,
            'started_at' => now(),
        ]);

        return redirect()->route('products.index')->with('success', 'Shift started.');
    }

    // --- EMPLOYEE: CLOSE REGISTER FORM ---
    public function edit(Shift $shift)
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('shifts.index');
        }

        if ($shift->user_id !== Auth::id() || $shift->ended_at) {
            abort(403);
        }

        // Calculate Cash Sales for this shift
        $cashSales = Order::where('user_id', Auth::id())
            ->where('created_at', '>=', $shift->started_at)
            ->where('payment_mode', 'cash')
            ->where('status', '!=', 'voided')
            ->sum('total_price');

        $expectedCash = $shift->start_cash + $cashSales;

        return view('shifts.edit', compact('shift', 'expectedCash', 'cashSales'));
    }

    // --- EMPLOYEE: END SHIFT ---
    public function update(Request $request, Shift $shift)
    {
        if (Auth::user()->role === 'admin') {
            abort(403);
        }

        $request->validate(['end_cash' => 'required|numeric|min:0']);

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

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Shift ended. Sales recorded: ₱' . number_format($cashSales, 2));
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