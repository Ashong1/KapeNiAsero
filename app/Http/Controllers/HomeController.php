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
        // If the user is NOT an admin, send them straight to the POS page.
        if ($user->role !== 'admin') {
            return redirect()->route('products.index');
        }

        // 2. ADMIN DASHBOARD DATA
        // Calculate Total Sales Today
        $todaySales = Order::whereDate('created_at', Carbon::today())->sum('total_price');
        
        // Count Orders Today
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();

        // Find Ingredients that are running low (Stock <= Alert Level)
        $lowStockIngredients = Ingredient::whereColumn('stock', '<=', 'reorder_level')->get();

        // Get the 5 most recent orders to show in a list
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Send all this data to the dashboard view
        return view('home', compact('todaySales', 'todayOrders', 'lowStockIngredients', 'recentOrders'));
    }
}