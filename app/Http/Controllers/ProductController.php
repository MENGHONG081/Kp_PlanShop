<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all products with pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 12);
        $products = Product::with('category')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ]
        ], 200);
    }

    /**
     * Get single product by ID
     */
    public function show(Product $product)
    {
        $product->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product
        ], 200);
    }

    /**
     * Search products by name or description
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $perPage = $request->input('per_page', 12);

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required',
                'data' => []
            ], 400);
        }

        $products = Product::with('category')
            ->where('name', 'ilike', "%{$query}%")
            ->orWhere('description', 'ilike', "%{$query}%")
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Products found',
            'data' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ]
        ], 200);
    }

    /**
     * Get all categories
     */
    public function categories()
    {
        $categories = Category::withCount('products')->get();

        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $categories
        ], 200);
    }

    /**
     * Get products by category
     */
    public function byCategory(Category $category, Request $request)
    {
        $perPage = $request->input('per_page', 12);
        $products = $category->products()
            ->with('category')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products->items(),
            'category' => $category,
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ]
        ], 200);
    }
}
