<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function index(Request $request) {
        $products = Product::with('category')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->orderBy('name', $request->get('sort', 'asc'))
            ->paginate(10);
        return ProductResource::collection($products);
    }

    public function show($id) {
        $product = Product::with('category')->findOrFail($id);
        return new ProductResource($product);
    }
}
