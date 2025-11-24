<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // 1. Get all products from the database
    $products = Product::all();

    // 2. Send them to the 'index' view
    return view('products.index', compact('products'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    // Show the form to add a new coffee
    return view('products.create');
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // We added 'stock' => 'required|integer' to the list below
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'stock' => 'required|integer' // <--- THIS IS THE NEW LINE
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
                        ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    // 1. Show the Edit Form (Pre-filled with data)
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // 2. Update the Data in Database
    public function update(Request $request, Product $product)
    {
        // We added the stock check here too
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'stock' => 'required|integer' // <--- THIS IS THE NEW LINE
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
                        ->with('success', 'Product updated successfully');
    }
    // 3. Delete the Item
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
                        ->with('success', 'Product deleted successfully');
    }
}