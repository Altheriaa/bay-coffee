<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();

        return inertia('Shop/Index', [
            'products' => $products,
        ]);     
    }

    public function show($id) {

        $product = Product::with('category')->where('id', $id)->first();

        if (!$product) {
            return abort(404);
        }

        return inertia('Shop/Show', [
            'product' => $product,
        ]);
    }
}
