<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InventoryApiController extends Controller
{
    /**
     * GET /api/inventory/ingredients
     * List all ingredients with their supplier.
     */
    public function index()
    {
        $ingredients = Ingredient::with('supplier')->get();
        
        return response()->json([
            'status' => 'success',
            'count' => $ingredients->count(),
            'data' => $ingredients
        ]);
    }

    /**
     * POST /api/inventory/ingredients
     * Create a new ingredient.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'unit' => 'required|string',
            'stock' => 'required|numeric|min:0',
            'reorder_level' => 'required|numeric',
            'supplier_id' => 'nullable|exists:suppliers,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $ingredient = Ingredient::create($request->all());

        // Log Initial Stock if > 0
        if ($request->stock > 0) {
            InventoryLog::create([
                'ingredient_id' => $ingredient->id,
                'user_id' => Auth::id() ?? 1, // Fallback to ID 1 if auth not fully set up
                'type' => 'initial_stock',
                'quantity_change' => $request->stock,
                'running_balance' => $request->stock,
                'remarks' => 'Initial setup via API',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient created successfully',
            'data' => $ingredient
        ], 201);
    }

    /**
     * GET /api/inventory/ingredients/{id}
     * Show details of a specific ingredient.
     */
    public function show($id)
    {
        $ingredient = Ingredient::with('supplier')->find($id);

        if (!$ingredient) {
            return response()->json(['status' => 'error', 'message' => 'Ingredient not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $ingredient]);
    }

    /**
     * POST /api/inventory/ingredients/{id}/restock
     * Add stock to an ingredient (Stock In).
     */
    public function restock(Request $request, $id)
    {
        $ingredient = Ingredient::find($id);
        if (!$ingredient) {
            return response()->json(['status' => 'error', 'message' => 'Ingredient not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric|min:0.1',
            'unit_cost' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // 1. Update Stock
            $newBalance = $ingredient->stock + $request->quantity;
            $ingredient->update(['stock' => $newBalance]);

            // 2. Log Movement
            $log = InventoryLog::create([
                'ingredient_id' => $ingredient->id,
                'user_id' => Auth::id() ?? 1,
                'type' => 'restock',
                'quantity_change' => $request->quantity,
                'running_balance' => $newBalance,
                'unit_cost' => $request->unit_cost,
                'remarks' => $request->remarks ?? 'Restock via API',
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Restocked {$request->quantity} {$ingredient->unit} to {$ingredient->name}",
                'new_balance' => $newBalance,
                'log' => $log
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * PUT /api/inventory/ingredients/{id}
     * Update ingredient details or manually adjust stock.
     */
    public function update(Request $request, $id)
    {
        $ingredient = Ingredient::find($id);
        if (!$ingredient) {
            return response()->json(['status' => 'error', 'message' => 'Ingredient not found'], 404);
        }

        // Validate inputs
        $validator = Validator::make($request->all(), [
            'name'          => 'sometimes|required|string|max:255',
            'supplier_id'   => 'nullable|exists:suppliers,id',
            'unit'          => 'sometimes|required|string',
            'reorder_level' => 'sometimes|numeric|min:0',
            'stock'         => 'sometimes|numeric|min:0', // For manual adjustment
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // 1. Update Basic Details
        $ingredient->fill($request->only(['name', 'unit', 'reorder_level']));
        
        if ($request->has('supplier_id')) {
            $ingredient->supplier_id = $request->supplier_id;
        }

        // 2. Handle Stock Adjustment
        if ($request->has('stock')) {
            $oldStock = $ingredient->stock;
            $newStock = $request->stock;
            $diff = $newStock - $oldStock;

            if ($diff != 0) {
                $ingredient->stock = $newStock;
                
                InventoryLog::create([
                    'ingredient_id' => $ingredient->id,
                    'user_id' => Auth::id() ?? 1,
                    'type' => 'manual_adjustment',
                    'quantity_change' => $diff,
                    'running_balance' => $newStock,
                    'remarks' => 'Manual Update via API',
                ]);
            }
        }

        $ingredient->save();

        return response()->json([
            'status' => 'success', 
            'message' => 'Ingredient updated successfully',
            'data' => $ingredient
        ]);
    }

    /**
     * GET /api/inventory/ingredients/{id}/history
     * Get stock movement history.
     */
    public function history($id)
    {
        $ingredient = Ingredient::find($id);
        if (!$ingredient) {
            return response()->json(['status' => 'error', 'message' => 'Ingredient not found'], 404);
        }

        $logs = InventoryLog::where('ingredient_id', $id)
                            ->with('user:id,name') // Only fetch user name and id
                            ->latest()
                            ->paginate(15);

        return response()->json([
            'status' => 'success',
            'ingredient' => $ingredient->name,
            'data' => $logs
        ]);
    }

    /**
     * DELETE /api/inventory/ingredients/{id}
     * Remove an ingredient.
     */
    public function destroy($id)
    {
        $ingredient = Ingredient::find($id);

        if (!$ingredient) {
            return response()->json(['status' => 'error', 'message' => 'Ingredient not found'], 404);
        }

        // Optional: Check if used in products before deleting
        // if($ingredient->products()->exists()) { ... }

        $ingredient->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient deleted successfully via API'
        ]);
    }
}