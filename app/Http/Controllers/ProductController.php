<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Imported for deleting old images

class ProductController extends Controller
{
    public function index()
    {
        // Fetch products with category relationship for optimization
        $products = Product::with('category')->get(); 
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048', // Validation for image (max 2MB)
        ]);

        $data = $request->all();

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Stores the file in 'storage/app/public/products' and returns the path (e.g., 'products/filename.jpg')
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path; // Add the path to the data array
        }

        $product = Product::create($data);

        return redirect()->route('products.edit', $product->id)
                         ->with('success', 'Product created! Now add the recipe ingredients.');
    }

    public function edit(Product $product)
    {
        $ingredients = Ingredient::all();
        $categories = Category::all();
        return view('products.edit', compact('product', 'ingredients', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists to clean up storage
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        // Delete the image file when the product is deleted
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

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