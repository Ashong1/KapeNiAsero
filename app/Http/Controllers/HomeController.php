<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ingredient;
use App\Models\Shift;
use App\Models\ParkedOrder; // [NEW]
use App\Models\ActivityLog; // [NEW]
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            if (!$activeShift) return redirect()->route('shifts.create');
            return redirect()->route('products.index');
        }

        // 3. ADMIN DASHBOARD DATA
        
        // Basic Stats (Today)
        $todayOrdersQuery = Order::whereDate('created_at', Carbon::today())
                                 ->where('status', 'completed');
                                 
        $todaySales = $todayOrdersQuery->sum('total_price');
        $todayOrders = $todayOrdersQuery->count();

        // [NEW] Order Type Stats (Dine-in vs Take-out)
        $orderStats = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->selectRaw("SUM(CASE WHEN order_type = 'dine_in' THEN 1 ELSE 0 END) as dine_in")
            ->selectRaw("SUM(CASE WHEN order_type = 'take_out' THEN 1 ELSE 0 END) as take_out")
            ->first();

        // [NEW] Parked Orders Count
        $parkedCount = ParkedOrder::count();

        // [NEW] System Logs (Voids, Restocks, Logins)
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();

        // Stock & Recent Orders
        $lowStockIngredients = Ingredient::whereColumn('stock', '<=', 'reorder_level')->get();
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Chart Data (Last 7 Days)
        $salesLabels = [];
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $salesLabels[] = $date->format('D');
            $salesData[] = Order::whereDate('created_at', $date)
                                ->where('status', 'completed')
                                ->sum('total_price');
        }

        // Active Staff & Top Server
        $activeStaff = Shift::whereNull('ended_at')->with('user')->get();
        
        $topServer = Order::whereDate('created_at', Carbon::today())
                          ->where('status', 'completed')
                          ->select('user_id', DB::raw('SUM(total_price) as total_sales'), DB::raw('COUNT(*) as order_count'))
                          ->groupBy('user_id')
                          ->orderByDesc('total_sales')
                          ->with('user')
                          ->first();

        return view('home', compact(
            'todaySales', 'todayOrders', 'lowStockIngredients', 
            'recentOrders', 'salesLabels', 'salesData', 'activeShift',
            'activeStaff', 'topServer', 
            'parkedCount', 'orderStats', 'recentLogs' // [Passed New Variables]
        ));
    }
}