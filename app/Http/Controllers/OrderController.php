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
            // Discount Validation
            'discount' => 'nullable|array',
            'discount.type' => 'nullable|in:fixed,percentage,none',
            // Cash Validation
            'cash_tendered' => 'required|numeric|min:0', 
        ]);

        try {
            DB::beginTransaction();
            $subtotal = 0;

            // 1. Stock Check & Subtotal Calculation
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
                $subtotal += $product->price * $qtyOrdered;
            }

            // 2. Calculate Discount
            $discountAmount = 0;
            $discountName = null;
            
            if ($request->has('discount') && $request->discount['type'] !== 'none') {
                $type = $request->discount['type'];
                $val = floatval($request->discount['value']);
                $discountName = $request->discount['name'] ?? 'Discount';

                if ($type === 'percentage') {
                    $discountAmount = $subtotal * ($val / 100);
                } elseif ($type === 'fixed') {
                    $discountAmount = $val;
                }
                
                // Prevent negative total
                if ($discountAmount > $subtotal) {
                    $discountAmount = $subtotal;
                }
            }

            $finalTotal = $subtotal - $discountAmount;
            
            // 3. Calculate Change
            $cashTendered = floatval($request->cash_tendered);
            
            // Ensure enough cash provided
            // Note: Using round() to avoid floating point precision issues
            if (round($cashTendered, 2) < round($finalTotal, 2)) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Insufficient cash tendered.']);
            }
            
            $changeAmount = $cashTendered - $finalTotal;

            // 4. Create Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount_name' => $discountName,
                'discount_amount' => $discountAmount,
                'total_price' => $finalTotal,
                'cash_tendered' => $cashTendered,   // <--- Save Cash
                'change_amount' => $changeAmount,   // <--- Save Change
                'payment_mode' => 'cash',
                'order_type' => $request->order_type ?? 'dine_in',
            ]);

            // 5. Save Items & Deduct Inventory
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
                    'modifiers' => $item['modifiers'] ?? null,
                ]);
            }

            DB::commit();
            $this->logActivity('New Order', "Order #{$order->id} - Total: {$order->total_price}");

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