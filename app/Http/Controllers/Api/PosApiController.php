<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class PosApiController extends Controller
{
    /**
     * Fetch products for the POS system.
     * This endpoint is consumed by the frontend via fetch().
     */
    public function getProducts(Request $request)
    {
        // Start Query
        $query = Product::with('category');

        // Search Filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get();

        return response()->json([
            'status' => 'success',
            'count' => $products->count(),
            'data' => $products
        ]);
    }
}