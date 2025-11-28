<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Category; // <--- NEW IMPORT
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Eager load category to prevent N+1 query performance issues
        $products = Product::with('category')->get(); 
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all(); // <--- NEW: Fetch categories
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id', // <--- CHANGED VALIDATION
        ]);

        $product = Product::create($request->all());

        return redirect()->route('products.edit', $product->id)
                         ->with('success', 'Product created! Now add the recipe ingredients.');
    }

    public function edit(Product $product)
    {
        $ingredients = Ingredient::all();
        $categories = Category::all(); // <--- NEW: Fetch for edit dropdown
        return view('products.edit', compact('product', 'ingredients', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id', // <--- CHANGED
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    // ... (Keep destroy, addIngredient, removeIngredient exactly as they are) ...
    
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }

    public function addIngredient(Request $request, Product $product)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.1'
        ]);

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