<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::all();
        return view('ingredients.index', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'unit' => 'required|string',
            'stock' => 'required|numeric|min:0',
            'reorder_level' => 'required|numeric'
        ]);

        Ingredient::create($request->all());
        return redirect()->back()->with('success', 'Ingredient added to warehouse.');
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate(['stock' => 'required|numeric|min:0']);
        $ingredient->update($request->only('stock')); // Only update stock for quick edit
        return redirect()->back()->with('success', 'Stock updated.');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->back()->with('success', 'Ingredient deleted.');
    }
}