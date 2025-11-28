<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str for slug generation

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // Show form to create new category
    public function create()
    {
        return view('categories.create');
    }

    // Save the new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Auto-generate slug (e.g., "Hot Coffee" -> "hot-coffee")
        ]);

        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }

    // Show form to edit existing category
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Update the category
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    // Delete the category
    public function destroy(Category $category)
    {
        // Optional: Check if products are using this category first?
        // For now, we just delete it (Products will set category_id to NULL due to your migration settings)
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}