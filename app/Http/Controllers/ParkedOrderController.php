<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkedOrder;

class ParkedOrderController extends Controller
{
    // POST: Save current cart
    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array|min:1',
            'note' => 'nullable|string|max:50'
        ]);

        ParkedOrder::create([
            'user_id' => auth()->id(),
            'customer_note' => $request->note ?? 'Saved Order ' . date('H:i'),
            'cart_data' => $request->cart
        ]);

        return response()->json(['success' => true, 'message' => 'Order parked successfully.']);
    }

    // GET: List all parked orders
    public function index()
    {
        $orders = ParkedOrder::with('user')->latest()->get();
        return response()->json($orders);
    }

    // GET: Retrieve and Delete (Restore to cart)
    public function retrieve(ParkedOrder $order)
    {
        $data = $order->cart_data;
        $order->delete(); // Remove from parked list once restored
        return response()->json(['success' => true, 'cart' => $data]);
    }

    // DELETE: Delete without restoring
    public function destroy(ParkedOrder $order)
    {
        $order->delete();
        return response()->json(['success' => true]);
    }

    // PUT: Update the note on a parked order (FIXES MISSING REQUIREMENT)
    public function update(Request $request, ParkedOrder $order)
    {
        $request->validate([
            'note' => 'required|string|max:50'
        ]);

        $order->update([
            'customer_note' => $request->note
        ]);

        return response()->json(['success' => true, 'message' => 'Order note updated.']);
    }
}