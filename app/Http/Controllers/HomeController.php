<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem; // [NEW] Needed for top products
use App\Models\Ingredient;
use App\Models\Shift;
use App\Models\ParkedOrder;
use App\Models\ActivityLog;
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
        
        // --- BASIC STATS (TODAY) ---
        $todayOrdersQuery = Order::whereDate('created_at', Carbon::today())
                                 ->where('status', 'completed');
                                 
        $todaySales = $todayOrdersQuery->sum('total_price');
        $todayOrders = $todayOrdersQuery->count();
        
        // [NEW] Average Order Value (AOV)
        $averageOrderValue = $todayOrders > 0 ? $todaySales / $todayOrders : 0;

        // [NEW] Total Discounts Given
        $todayDiscounts = $todayOrdersQuery->sum('discount_amount');

        // [NEW] Payment Method Breakdown (Cash vs Digital)
        $paymentStats = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->select('payment_mode', DB::raw('SUM(total_price) as total'))
            ->groupBy('payment_mode')
            ->pluck('total', 'payment_mode'); // e.g. ['cash' => 1000, 'gcash' => 500]

        // [NEW] Voided Orders Stats
        $voidStats = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'voided')
            ->selectRaw('COUNT(*) as count, SUM(total_price) as total_amount')
            ->first();

        // --- EXISTING STATS ---
        $orderStats = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->selectRaw("SUM(CASE WHEN order_type = 'dine_in' THEN 1 ELSE 0 END) as dine_in")
            ->selectRaw("SUM(CASE WHEN order_type = 'take_out' THEN 1 ELSE 0 END) as take_out")
            ->first();

        $parkedCount = ParkedOrder::count();
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();
        $lowStockIngredients = Ingredient::whereColumn('stock', '<=', 'reorder_level')->get();
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // --- CHARTS (Last 7 Days) ---
        $salesLabels = [];
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $salesLabels[] = $date->format('D');
            $salesData[] = Order::whereDate('created_at', $date)
                                ->where('status', 'completed')
                                ->sum('total_price');
        }

        // --- ACTIVE STAFF & TOP SERVER ---
        $activeStaff = Shift::whereNull('ended_at')->with('user')->get();
        
        $topServer = Order::whereDate('created_at', Carbon::today())
                          ->where('status', 'completed')
                          ->select('user_id', DB::raw('SUM(total_price) as total_sales'), DB::raw('COUNT(*) as order_count'))
                          ->groupBy('user_id')
                          ->orderByDesc('total_sales')
                          ->with('user')
                          ->first();

        // [NEW] TOP SELLING PRODUCTS (Today)
        $topProducts = OrderItem::whereHas('order', function($q) {
                $q->whereDate('created_at', Carbon::today())
                  ->where('status', 'completed');
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product') // Eager load product name
            ->take(5)
            ->get();

        return view('home', compact(
            'todaySales', 'todayOrders', 'lowStockIngredients', 
            'recentOrders', 'salesLabels', 'salesData', 'activeShift',
            'activeStaff', 'topServer', 'parkedCount', 'orderStats', 
            'recentLogs', 
            // New Variables Passed to View
            'averageOrderValue', 'todayDiscounts', 'paymentStats', 
            'voidStats', 'topProducts'
        ));
    }
}