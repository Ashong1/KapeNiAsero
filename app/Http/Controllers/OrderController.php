<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 

class OrderController extends Controller
{
    public function index()
    {
        // Get all orders, latest first, 10 per page
        $orders = Order::with('user')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

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

            // 1. Stock Check & Total Calculation
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

            // 2. Create Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $totalAmount,
                'payment_mode' => 'cash',
            ]);

            // 3. Save Items & Deduct Inventory
            foreach ($request->cart as $item) {
                $product = Product::find($item['id']);
                
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
            $this->logActivity('New Order', "Order #{$order->id} - Total: {$order->total_price}");

            // --- KITCHEN FEATURE REMOVED ---

            // Return success with order_id for the frontend to print receipt
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

    // --- PDF RECEIPT ---
    public function downloadReceipt(Order $order)
    {
        $order->load(['items.product', 'user']);
        $pdf = Pdf::loadView('orders.receipt', compact('order'));
        return $pdf->stream('receipt-'.$order->id.'.pdf');
    }

    // --- VOID REQUESTS ---
    public function requestVoid(Order $order)
    {
        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'Only completed orders can be requested for void.');
        }

        $order->update(['status' => 'void_pending']);
        $employeeName = auth()->user()->name;
        $this->logActivity('Void Requested', "Void requested for Order #{$order->id} by {$employeeName}");

        return redirect()->back()->with('success', 'Void request submitted for Admin approval.');
    }

    // --- ADMIN VOID APPROVAL ---
    public function voidOrder(Order $order)
    {
        if ($order->status === 'voided') {
            return redirect()->back()->with('error', 'Order is already voided.');
        }

        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product && !$product->ingredients->isEmpty()) {
                foreach ($product->ingredients as $ingredient) {
                    $quantityUsed = $ingredient->pivot->quantity_needed * $item->quantity;
                    $ingredient->increment('stock', $quantityUsed);
                }
            }
        }

        $order->update(['status' => 'voided']);
        $this->logActivity('Void Order', "Voided Order #{$order->id}");

        return redirect()->back()->with('success', "Order #{$order->id} has been voided and inventory restored.");
    }
}