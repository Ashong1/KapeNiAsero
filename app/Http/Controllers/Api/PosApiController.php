<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class PosApiController extends Controller
{
    /**
     * Get all active products with their categories and ingredients.
     */
    public function getProducts(Request $request) // Add Request $request
    {
        $query = Product::with(['category', 'ingredients'])->where('stock', '>', 0);
    
        // Filter by search term if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }
    
        return response()->json($query->get());
    }
    /**
     * Get all categories for the POS tabs.
     */
    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Place a new order from POS.
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,gcash',
            'total_price' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $request->user()->id, 
                'total_price' => $request->total_price,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'order_type' => $request->order_type ?? 'dine_in',
            ]);

            foreach ($request->items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'], 
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Order placed successfully', 'order_id' => $order->id], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Order failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get recent orders for this POS user (History).
     */
    public function getMyOrders(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->take(20) 
            ->get();

        return response()->json($orders);
    }
}