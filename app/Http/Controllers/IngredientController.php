<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Supplier; // <--- Import Supplier Model

class IngredientController extends Controller
{
    public function index()
    {
        // Fetch ingredients with their linked supplier (eager loading)
        $ingredients = Ingredient::with('supplier')->get();
        
        // Fetch all suppliers for the dropdown list
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
            'supplier_id' => 'nullable|exists:suppliers,id' // <--- Validate Supplier
        ]);

        Ingredient::create($request->all());
        return redirect()->back()->with('success', 'Ingredient added to warehouse.');
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'stock' => 'required|numeric|min:0',
            // Optional: Allow updating other fields if needed in the future
        ]);
        
        $ingredient->update($request->only('stock')); 
        return redirect()->back()->with('success', 'Stock updated.');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->back()->with('success', 'Ingredient deleted.');
    }
}