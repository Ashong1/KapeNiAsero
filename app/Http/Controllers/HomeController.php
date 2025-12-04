<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Ingredient;
use App\Models\ActivityLog;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // --- EMPLOYEE LOGIC ---
        // If user is NOT admin, manage their flow based on shift status
        if ($user->role !== 'admin') {
            // Check for an active shift for this specific user
            $activeShift = Shift::where('user_id', $user->id)->whereNull('ended_at')->first();

            if (!$activeShift) {
                // No active shift? Force them to the "Start Shift" screen
                return redirect()->route('shifts.create');
            }

            // Has active shift? Go to POS
            return redirect()->route('orders.index');
        }

        // --- ADMIN DASHBOARD LOGIC ---
        
        $today = Carbon::today();

        // 1. KPIs
        $todayOrdersList = Order::whereDate('created_at', $today)->where('status', 'completed')->get();
        $todaySales = $todayOrdersList->sum('total_price');
        $todayOrdersCount = $todayOrdersList->count(); 
        $averageOrderValue = $todayOrdersCount > 0 ? $todaySales / $todayOrdersCount : 0;
        
        // 2. Parked Orders
        $parkedCount = DB::table('parked_orders')->count();

        // 3. Low Stock
        $lowStockIngredients = Ingredient::whereColumn('stock', '<=', 'reorder_level')->get();

        // 4. Top Server
        $topServer = Order::whereDate('created_at', $today)
                          ->where('status', 'completed')
                          ->select('user_id', DB::raw('SUM(total_price) as total_sales'), DB::raw('COUNT(*) as order_count'))
                          ->groupBy('user_id')
                          ->orderByDesc('total_sales')
                          ->with('user')
                          ->first();

        // 5. Best Sellers
        $topProducts = DB::table('order_items')
                         ->join('orders', 'order_items.order_id', '=', 'orders.id')
                         ->join('products', 'order_items.product_id', '=', 'products.id')
                         ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
                         ->whereDate('orders.created_at', $today)
                         ->where('orders.status', 'completed')
                         ->groupBy('order_items.product_id')
                         ->orderByDesc('total_sold')
                         ->limit(5)
                         ->get();
        
        foreach($topProducts as $item) {
            $item->product = Product::find($item->product_id);
        }

        // 6. Weekly Chart Data
        $salesDataRaw = Order::where('status', 'completed')
                             ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
                             ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as daily_total'))
                             ->groupBy('date')
                             ->get()
                             ->pluck('daily_total', 'date');

        $salesLabels = [];
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $salesLabels[] = now()->subDays($i)->format('M d');
            $salesData[] = $salesDataRaw[$date] ?? 0;
        }

        // 7. Active Staff List (This fixes the "On Duty" display)
        // Get ALL shifts that haven't ended yet
        $activeStaff = Shift::whereNull('ended_at')->with('user')->get();
        
        // Also get current user's shift for context if needed
        $activeShift = Shift::where('user_id', Auth::id())->whereNull('ended_at')->first();

        // 8. Recent Activity
        $recentOrders = Order::latest()->take(5)->with('user')->get();
        $recentLogs = ActivityLog::latest()->take(5)->with('user')->get();

        return view('home', compact(
            'todaySales', 'averageOrderValue', 'parkedCount', 
            'lowStockIngredients', 'topServer', 'topProducts', 'salesLabels', 
            'salesData', 'activeShift', 'activeStaff', 'recentOrders', 'recentLogs'
        ))->with('todayOrders', $todayOrdersCount);
    }
}