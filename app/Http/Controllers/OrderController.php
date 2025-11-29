<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 
use App\Events\OrderPlaced;

// --- HARDWARE IMPORTS ---
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

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

            // --- 4. KITCHEN DISPLAY (WebSocket) ---
            // Send the order to the kitchen screen instantly
            OrderPlaced::dispatch($order);

            // --- 5. THERMAL PRINTER (Hardware) ---
            // Print receipt and kick cash drawer open
            $this->printDirectReceipt($order);

            return response()->json([
                'success' => true, 
                'message' => 'Order complete! Printing receipt...', 
                'order_id' => $order->id 
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // --- PDF FALLBACK (Optional) ---
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

    // ==========================================
    // PRIVATE: HARDWARE PRINTING LOGIC
    // ==========================================
    private function printDirectReceipt($order)
    {
        try {
            // 1. Connect to Windows Shared Printer
            // Ensure you shared your printer in Control Panel as "POS-80"
            $connector = new WindowsPrintConnector(env('PRINTER_NAME', 'POS-80'));
            $printer = new Printer($connector);

            // 2. Initialize Printer
            $printer->initialize();
            
            // Header
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("KAPE NI ASERO\n");
            $printer->setEmphasis(false);
            $printer->text("Bay, Laguna\n");
            $printer->text("--------------------------------\n");

            // Order Details
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Order #: " . $order->id . "\n");
            $printer->text("Date: " . $order->created_at->format('Y-m-d h:i A') . "\n");
            $printer->text("Cashier: " . auth()->user()->name . "\n");
            $printer->text("--------------------------------\n");

            // Items Loop
            foreach ($order->items as $item) {
                // Layout: Qty x Name ...... Price
                // %-2s : Left align quantity (2 chars)
                // %-16.16s : Left align name (max 16 chars)
                // %8s : Right align price
                $line = sprintf("%-2s x %-16.16s %8s\n", 
                    $item->quantity, 
                    $item->product->name, 
                    number_format($item->price * $item->quantity, 2)
                );
                $printer->text($line);
            }

            // Footer / Totals
            $printer->text("--------------------------------\n");
            $printer->setEmphasis(true);
            $printer->text(sprintf("TOTAL: %24s\n", "P " . number_format($order->total_price, 2)));
            $printer->setEmphasis(false);
            $printer->text("\n");
            
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Thank you for brewing with us!\n");
            $printer->text("\n\n");

            // 3. HARDWARE ACTIONS
            $printer->pulse(); // Open Cash Drawer
            $printer->cut();   // Cut Paper
            
            // 4. Close Connection
            $printer->close();

        } catch (\Exception $e) {
            // If printer is off or paper is out, log the error but DO NOT crash the system.
            // This ensures the order is still saved in the database.
            \Log::error("POS Hardware Error: " . $e->getMessage());
        }
    }
}