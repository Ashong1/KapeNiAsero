<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ActivityLog; // [IMPORTED] Fixed missing import
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; 
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\Auth;

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
            'discount' => 'nullable|array',
            'discount.type' => 'nullable|in:fixed,percentage,none',
            'cash_tendered' => 'required|numeric|min:0', 
            'payment_mode' => 'required|in:cash,gcash,card', 
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
                if ($discountAmount > $subtotal) $discountAmount = $subtotal;
            }

            $finalTotal = $subtotal - $discountAmount;
            
            // 3. Create Order (Initially PENDING)
            $cashTendered = floatval($request->cash_tendered);
            
            // Check cash only if paying by cash
            if ($request->payment_mode === 'cash') {
                if (round($cashTendered, 2) < round($finalTotal, 2)) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Insufficient cash tendered.']);
                }
            }

            $changeAmount = $request->payment_mode === 'cash' ? ($cashTendered - $finalTotal) : 0;

            $order = Order::create([
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount_name' => $discountName,
                'discount_amount' => $discountAmount,
                'total_price' => $finalTotal,
                'cash_tendered' => $cashTendered,
                'change_amount' => $changeAmount,
                'payment_mode' => $request->payment_mode,
                'status' => 'pending', 
                'order_type' => $request->order_type ?? 'dine_in',
            ]);

            // 4. Save Items & Deduct Inventory
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

            // 5. HANDLE PAYMENT MODES
            if ($request->payment_mode === 'cash') {
                $order->update(['status' => 'completed']);
                DB::commit();
                
                // [FIXED] Log directly using Model
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'New Order',
                    'details' => "Order #{$order->id} (Cash) - Total: {$order->total_price}"
                ]);

                return response()->json(['success' => true, 'message' => 'Order complete!', 'order_id' => $order->id]);
            } else {
                // --- PAYMONGO INTEGRATION ---
                DB::commit(); 
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode(config('services.paymongo.secret_key')),
                ])->post('https://api.paymongo.com/v1/checkout_sessions', [
                    'data' => [
                        'attributes' => [
                            'billing' => [
                                'name' => auth()->user()->name,
                                'email' => auth()->user()->email,
                            ],
                            'line_items' => [[
                                'currency' => 'PHP',
                                'amount' => (int) ($finalTotal * 100), // Convert to cents
                                'name' => 'Order #' . $order->id,
                                'quantity' => 1,
                            ]],
                            'payment_method_types' => ['gcash', 'card', 'paymaya'],
                            'success_url' => route('orders.success', $order->id),
                            'cancel_url' => route('orders.index'),
                            'reference_number' => (string) $order->id,
                        ]
                    ]
                ]);

                $data = $response->json();

                if (isset($data['data']['attributes']['checkout_url'])) {
                    return response()->json([
                        'success' => true, 
                        'redirect_url' => $data['data']['attributes']['checkout_url']
                    ]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Payment Gateway Error: ' . json_encode($data)]);
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function paymentSuccess(Order $order)
    {
        if ($order->status === 'pending') {
            $order->update(['status' => 'completed']);
            
            // [FIXED] Log directly using Model
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'New Order',
                'details' => "Order #{$order->id} ({$order->payment_mode}) - Total: {$order->total_price}"
            ]);
        }
        
        return redirect()->route('orders.index')->with('success', "Payment Successful! Order #{$order->id} is completed.");
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

        // [FIXED] Log directly using Model
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Void Requested',
            'details' => "Void requested for Order #{$order->id} by {$employeeName}"
        ]);

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
        
        // [FIXED] Log directly using Model
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Void Order',
            'details' => "Voided Order #{$order->id}"
        ]);

        return redirect()->back()->with('success', "Order #{$order->id} has been voided and inventory restored.");
    }
}