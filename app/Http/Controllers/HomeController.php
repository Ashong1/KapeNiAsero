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
        $today = Carbon::today();

        // 1. Fetch Today's Completed Orders
        $todayOrdersList = Order::whereDate('created_at', $today)
                                ->where('status', 'completed')
                                ->get();

        // 2. Calculate Basic KPIs
        $todaySales = $todayOrdersList->sum('total_price');
        $todayOrdersCount = $todayOrdersList->count();
        $averageOrderValue = $todayOrdersCount > 0 ? $todaySales / $todayOrdersCount : 0;
        
        // FIX: Manually calculate Dine-in vs Take-out counts
        // Note: Assumes your DB column is 'type' or 'order_type'. Adjust 'type' below if needed.
        $orderStats = (object) [
            'dine_in' => $todayOrdersList->whereIn('type', ['dine_in', 'Dine-in'])->count(),
            'take_out' => $todayOrdersList->whereIn('type', ['take_out', 'Take-out'])->count(),
        ];

        // 3. Payment Methods (Cash vs Digital)
        $paymentStats = [
            'cash' => $todayOrdersList->where('payment_method', 'cash')->sum('total_price'),
            'gcash' => $todayOrdersList->where('payment_method', 'gcash')->sum('total_price'),
            'card' => $todayOrdersList->where('payment_method', 'card')->sum('total_price'),
        ];

        // 4. Discounts & Voids
        $todayDiscounts = 0; // If you have a discount column, sum it here: $todayOrdersList->sum('discount');
        
        $voidStats = Order::whereDate('created_at', $today)
                          ->where('status', 'voided')
                          ->selectRaw('count(*) as count, sum(total_price) as total_amount')
                          ->first();

        // 5. Parked/Hold Orders
        $parkedCount = DB::table('parked_orders')->count();

        // 6. Low Stock Ingredients
        $lowStockIngredients = Ingredient::whereColumn('stock', '<=', 'reorder_level')->get();

        // 7. Top Server (User with most sales today)
        $topServer = Order::whereDate('created_at', $today)
                          ->where('status', 'completed')
                          ->select('user_id', DB::raw('SUM(total_price) as total_sales'), DB::raw('COUNT(*) as order_count'))
                          ->groupBy('user_id')
                          ->orderByDesc('total_sales')
                          ->with('user')
                          ->first();

        // 8. Best Selling Products (Top 5 Today)
        $topProducts = DB::table('order_items')
                         ->join('orders', 'order_items.order_id', '=', 'orders.id')
                         ->join('products', 'order_items.product_id', '=', 'products.id') // Join products to get name/price
                         ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
                         ->whereDate('orders.created_at', $today)
                         ->where('orders.status', 'completed')
                         ->groupBy('order_items.product_id')
                         ->orderByDesc('total_sold')
                         ->limit(5)
                         ->get();
        
        // Hydrate product details manually to avoid complex relation loading on query builder
        foreach($topProducts as $item) {
            $item->product = Product::find($item->product_id);
        }

        // 9. Weekly Sales Chart Data
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
            $salesLabels[] = now()->subDays($i)->format('M d'); // e.g. "Oct 25"
            $salesData[] = $salesDataRaw[$date] ?? 0;
        }

        // 10. Active Staff & Shift
        $activeShift = Shift::where('user_id', Auth::id())->whereNull('ended_at')->first();
        $activeStaff = Shift::whereNull('ended_at')->with('user')->get();

        // 11. Recent Activity
        $recentOrders = Order::latest()->take(5)->with('user')->get();
        $recentLogs = ActivityLog::latest()->take(5)->with('user')->get();

        return view('home', compact(
            'todaySales', 'todayOrdersCount', 'averageOrderValue', 'orderStats', 
            'paymentStats', 'todayDiscounts', 'voidStats', 'parkedCount', 
            'lowStockIngredients', 'topServer', 'topProducts', 'salesLabels', 
            'salesData', 'activeShift', 'activeStaff', 'recentOrders', 'recentLogs'
        ))->with('todayOrders', $todayOrdersCount); // Alias for view compatibility
    }
}