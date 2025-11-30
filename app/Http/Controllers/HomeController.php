<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ingredient;
use App\Models\Shift; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // 1. CHECK FOR ACTIVE SHIFT
        $activeShift = Shift::where('user_id', $user->id)
                            ->whereNull('ended_at')
                            ->first();

        // 2. EMPLOYEE LOGIC
        if ($user->role !== 'admin') {
            // If NO shift is open, Force them to Open Register (Start Shift)
            if (!$activeShift) {
                return redirect()->route('shifts.create');
            }
            
            // If Shift IS open, Force them to POS (Selling)
            return redirect()->route('products.index');
        }

        // 3. ADMIN DASHBOARD (Same as before)
        $todaySales = Order::whereDate('created_at', Carbon::today())
                            ->where('status', '!=', 'voided')
                            ->sum('total_price');
        
        $todayOrders = Order::whereDate('created_at', Carbon::today())
                            ->where('status', '!=', 'voided')
                            ->count();

        $lowStockIngredients = Ingredient::whereColumn('stock', '<=', 'reorder_level')->get();
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        $salesLabels = [];
        $salesData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $salesLabels[] = $date->format('D');
            $salesData[] = Order::whereDate('created_at', $date)
                                ->where('status', '!=', 'voided')
                                ->sum('total_price');
        }

        return view('home', compact(
            'todaySales', 'todayOrders', 'lowStockIngredients', 
            'recentOrders', 'salesLabels', 'salesData', 'activeShift'
        ));
    }
}