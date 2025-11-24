<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB; // Needed for transactions

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;

            // 1. Check Stock Levels FIRST
            foreach ($request->cart as $item) {
                $product = Product::lockForUpdate()->find($item['id']); // Lock prevents double selling

                if ($product->stock < $item['quantity']) {
                    // If not enough stock, cancel everything!
                    DB::rollBack();
                    return response()->json([
                        'success' => false, 
                        'message' => "Not enough stock for {$product->name}. Only {$product->stock} left."
                    ]);
                }
                
                $totalAmount += $product->price * $item['quantity'];
            }

            // 2. Create Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $totalAmount,
                'payment_mode' => 'cash',
            ]);

            // 3. Deduct Stock & Save Items
            foreach ($request->cart as $item) {
                $product = Product::find($item['id']);
                
                // DEDUCT STOCK (The Inventory Integration)
                $product->decrement('stock', $item['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Order saved! Inventory updated.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}