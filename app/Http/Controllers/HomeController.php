<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Ingredient;
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

        // 1. TRAFFIC COP LOGIC
        // Redirect non-admin users to the POS page
        if ($user->role !== 'admin') {
            return redirect()->route('products.index');
        }

        // 2. ADMIN DASHBOARD DATA
        
        // Calculate Total Sales Today (EXCLUDING VOIDED ORDERS)
        $todaySales = Order::whereDate('created_at', Carbon::today())
                            ->where('status', '!=', 'voided')
                            ->sum('total_price');
        
        // Count Orders Today (EXCLUDING VOIDED ORDERS)
        $todayOrders = Order::whereDate('created_at', Carbon::today())
                            ->where('status', '!=', 'voided')
                            ->count();

        // Find Ingredients that are running low (Stock <= Alert Level)
        $lowStockIngredients = Ingredient::whereColumn('stock', '<=', 'reorder_level')->get();

        // Get the 5 most recent orders to show in a list
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // --- WEEKLY ANALYTICS LOGIC ---
        $salesLabels = [];
        $salesData = [];

        // Loop through the last 7 days (including today)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            // Push Day Name (e.g., "Mon") into labels array
            $salesLabels[] = $date->format('D');
            
            // Calculate Sales for that specific day and push to data array
            $daySales = Order::whereDate('created_at', $date)
                                ->where('status', '!=', 'voided')
                                ->sum('total_price');
            
            $salesData[] = $daySales;
        }

        // Send all this data to the dashboard view
        return view('home', compact(
            'todaySales', 
            'todayOrders', 
            'lowStockIngredients', 
            'recentOrders',
            'salesLabels', 
            'salesData'
        ));
    }
}