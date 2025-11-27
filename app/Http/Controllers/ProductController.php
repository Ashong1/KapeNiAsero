<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Ingredient; // Import Ingredient
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    // UPDATED EDIT FUNCTION
    public function edit(Product $product)
    {
        $ingredients = Ingredient::all(); // Load ingredients for dropdown
        return view('products.edit', compact('product', 'ingredients'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }

    // --- NEW FUNCTIONS FOR RECIPES ---

    public function addIngredient(Request $request, Product $product)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.1'
        ]);

        // Attach ingredient (if exists, update quantity)
        $product->ingredients()->syncWithoutDetaching([
            $request->ingredient_id => ['quantity_needed' => $request->quantity]
        ]);

        return redirect()->back()->with('success', 'Recipe updated.');
    }

    public function removeIngredient(Product $product, Ingredient $ingredient)
    {
        $product->ingredients()->detach($ingredient->id);
        return redirect()->back()->with('success', 'Ingredient removed from recipe.');
    }
}