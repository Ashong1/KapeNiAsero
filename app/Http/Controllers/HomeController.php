<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
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

        // 2. EMPLOYEE REDIRECT
        if ($user->role !== 'admin') {
            return $activeShift ? redirect()->route('products.index') : redirect()->route('shifts.create');
        }

        // 3. ADMIN DASHBOARD - OPTIMIZED QUERIES
        
        // --- TODAY'S STATS (Using Scopes) ---
        // Fetch all completed orders for today in one go
        $todayOrdersCollection = Order::completed()->whereDate('created_at', Carbon::today())->get();

        $todaySales = $todayOrdersCollection->sum('total_price');
        $todayOrders = $todayOrdersCollection->count();
        $averageOrderValue = $todayOrders > 0 ? $todaySales / $todayOrders : 0;
        $todayDiscounts = $todayOrdersCollection->sum('discount_amount');

        // Payment Breakdown (Calculated from Collection)
        $paymentStats = $todayOrdersCollection->groupBy('payment_mode')
            ->map(fn($row) => $row->sum('total_price'));

        // Void Stats
        $voidStats = Order::voided()->whereDate('created_at', Carbon::today())
            ->selectRaw('COUNT(*) as count, SUM(total_price) as total_amount')
            ->first();

        // Order Types (Dine-in vs Take-out)
        $orderStats = $todayOrdersCollection->groupBy('order_type')
            ->map(fn($row) => $row->count());

        // --- INVENTORY & LOGS ---
        $parkedCount = ParkedOrder::count();
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();
        $lowStockIngredients = Ingredient::whereColumn('stock', '<=', 'reorder_level')->get(); 
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // --- OPTIMIZED CHART (One Query for 7 Days) ---
        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today()->endOfDay();

        $weeklyOrders = Order::completed()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'), 
                DB::raw('SUM(total_price) as daily_total')
            )
            ->groupBy('date')
            ->get()
            ->keyBy('date'); // Key by date for easy lookup

        // Fill in missing days
        $salesLabels = [];
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $salesLabels[] = Carbon::parse($date)->format('D'); // Mon, Tue...
            $salesData[] = isset($weeklyOrders[$date]) ? $weeklyOrders[$date]->daily_total : 0;
        }

        // --- TOP PERFORMERS ---
        $activeStaff = Shift::whereNull('ended_at')->with('user')->get();
        
        // Top Server
        $topServer = Order::completed()
            ->whereDate('created_at', Carbon::today())
            ->select('user_id', DB::raw('SUM(total_price) as total_sales'), DB::raw('COUNT(*) as order_count'))
            ->groupBy('user_id')
            ->orderByDesc('total_sales')
            ->with('user')
            ->first();

        // Top Products
        $topProducts = OrderItem::whereHas('order', function($q) {
                $q->completed()->whereDate('created_at', Carbon::today());
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get();

        return view('home', compact(
            'todaySales', 'todayOrders', 'lowStockIngredients', 
            'recentOrders', 'salesLabels', 'salesData', 'activeShift',
            'activeStaff', 'topServer', 'parkedCount', 'orderStats', 
            'recentLogs', 'averageOrderValue', 'todayDiscounts', 
            'paymentStats', 'voidStats', 'topProducts'
        ));
    }
}