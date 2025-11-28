<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // <--- IMPORT THIS

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // ... (Keep validation logic exactly the same) ...
        $request->validate([
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();
            $totalAmount = 0;

            // ... (Keep Stock Check logic exactly the same) ...
            foreach ($request->cart as $item) {
                $product = Product::with('ingredients')->lockForUpdate()->find($item['id']);
                $qtyOrdered = $item['quantity'];

                if (!$product->ingredients->isEmpty()) {
                    foreach ($product->ingredients as $ing) {
                        $needed = $ing->pivot->quantity_needed * $qtyOrdered;
                        if ($ing->stock < $needed) {
                            DB::rollBack();
                            return response()->json([
                                'success' => false,
                                'message' => "Not enough {$ing->name}! Need {$needed}{$ing->unit}, have {$ing->stock}."
                            ]);
                        }
                    }
                }
                $totalAmount += $product->price * $qtyOrdered;
            }

            // 2. CREATE ORDER
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $totalAmount,
                'payment_mode' => 'cash',
            ]);

            // 3. DEDUCT STOCK & SAVE ITEMS
            foreach ($request->cart as $item) {
                $product = Product::find($item['id']);
                
                // Deduct Ingredients
                if (!$product->ingredients->isEmpty()) {
                    foreach ($product->ingredients as $ing) {
                        $needed = $ing->pivot->quantity_needed * $item['quantity'];
                        $ing->decrement('stock', $needed);
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            DB::commit();

            // CHANGE: Return the order_id so we can print the receipt
            return response()->json([
                'success' => true, 
                'message' => 'Order complete!', 
                'order_id' => $order->id 
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // --- NEW FUNCTION: GENERATE PDF ---
    public function downloadReceipt(Order $order)
    {
        // Load relationships to access product names and user name
        $order->load(['items.product', 'user']);
        
        // Generate PDF from a view
        $pdf = Pdf::loadView('orders.receipt', compact('order'));
        
        // Stream it (Open in browser) instead of force download
        return $pdf->stream('receipt-'.$order->id.'.pdf');
    }
}