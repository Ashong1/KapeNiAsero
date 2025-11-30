<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Supplier;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::with('supplier')->get();
        $suppliers = Supplier::all(); 
        return view('ingredients.index', compact('ingredients', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'unit' => 'required|string',
            'stock' => 'required|numeric|min:0',
            'reorder_level' => 'required|numeric',
            'supplier_id' => 'nullable|exists:suppliers,id'
        ]);

        $ingredient = Ingredient::create($request->all());

        // Log Initial Stock
        if ($request->stock > 0) {
            InventoryLog::create([
                'ingredient_id' => $ingredient->id,
                'user_id' => Auth::id(),
                'type' => 'initial_stock',
                'quantity_change' => $request->stock,
                'running_balance' => $request->stock,
                'remarks' => 'Initial setup',
            ]);
        }

        return redirect()->back()->with('success', 'Ingredient added to warehouse.');
    }

    // --- NEW: Restock Function (Stock In) ---
    public function restock(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.1',
            'unit_cost' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:255', // e.g. Invoice Number
        ]);

        try {
            DB::beginTransaction();

            // 1. Update Stock
            $newBalance = $ingredient->stock + $request->quantity;
            $ingredient->update(['stock' => $newBalance]);

            // 2. Log Movement
            InventoryLog::create([
                'ingredient_id' => $ingredient->id,
                'user_id' => Auth::id(),
                'type' => 'restock',
                'quantity_change' => $request->quantity,
                'running_balance' => $newBalance,
                'unit_cost' => $request->unit_cost,
                'remarks' => $request->remarks ?? 'Restock',
            ]);

            DB::commit();
            return redirect()->back()->with('success', "Added {$request->quantity} {$ingredient->unit} to {$ingredient->name}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating stock: ' . $e->getMessage());
        }
    }

    // --- NEW: Stock History (Stock Card) ---
    public function history(Ingredient $ingredient)
    {
        $logs = InventoryLog::where('ingredient_id', $ingredient->id)
                            ->with('user')
                            ->latest()
                            ->paginate(15);

        return view('ingredients.history', compact('ingredient', 'logs'));
    }

    // Updated: Handles both Manual Stock Adjustment AND Editing Details (Supplier, Name, etc.)
    public function update(Request $request, Ingredient $ingredient)
    {
        // Validate inputs (use 'sometimes' so we can update specific fields independently)
        $request->validate([
            'name'          => 'sometimes|required|string|max:255',
            'supplier_id'   => 'nullable|exists:suppliers,id', // Allow selecting a supplier or null
            'unit'          => 'sometimes|required|string',
            'reorder_level' => 'sometimes|numeric|min:0',
            'stock'         => 'sometimes|numeric|min:0', // Logic for stock adjustment
        ]);

        // 1. Update Basic Details if provided
        if ($request->has('name')) $ingredient->name = $request->name;
        if ($request->has('unit')) $ingredient->unit = $request->unit;
        if ($request->has('reorder_level')) $ingredient->reorder_level = $request->reorder_level;
        
        // Handle Supplier ID specifically (check exists so we can set it to null if sent)
        if ($request->exists('supplier_id')) {
            $ingredient->supplier_id = $request->supplier_id;
        }

        // 2. Handle Stock Adjustment (Only runs if 'stock' is in the form)
        if ($request->has('stock')) {
            $oldStock = $ingredient->stock;
            $newStock = $request->stock;
            $diff = $newStock - $oldStock;

            if ($diff != 0) {
                $ingredient->stock = $newStock;
                
                InventoryLog::create([
                    'ingredient_id' => $ingredient->id,
                    'user_id' => Auth::id(),
                    'type' => 'manual_adjustment',
                    'quantity_change' => $diff,
                    'running_balance' => $newStock,
                    'remarks' => 'Manual Update via Admin Panel',
                ]);
            }
        }

        $ingredient->save();
        
        return redirect()->back()->with('success', 'Ingredient details updated successfully.');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->back()->with('success', 'Ingredient deleted.');
    }
}