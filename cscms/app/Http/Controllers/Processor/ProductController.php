<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->get();
        return view('processor.products.index', compact('products'));
    }

    public function create()
    {
        return view('processor.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|in:green_beans,roasted_beans,ground_coffee',
            'origin_country' => 'nullable|string|max:100',
            'processing_method' => 'nullable|in:washed,natural,honey',
            'roast_level' => 'nullable|in:light,medium,dark',
            'quantity_kg' => 'required|numeric|min:0',
            'price_per_kg' => 'nullable|numeric|min:0',
            'quality_score' => 'nullable|numeric|min:1|max:10',
            'harvest_date' => 'nullable|date',
            'processing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'in:available,reserved,sold,expired',
        ]);

        Product::create(array_merge($request->all(), ['user_id' => Auth::id()]));

        return redirect()->route('processor.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        if ($product->user_id != Auth::id()) {
            abort(403);
        }

        return view('processor.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->user_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|in:green_beans,roasted_beans,ground_coffee',
            'origin_country' => 'nullable|string|max:100',
            'processing_method' => 'nullable|in:washed,natural,honey',
            'roast_level' => 'nullable|in:light,medium,dark',
            'quantity_kg' => 'required|numeric|min:0',
            'price_per_kg' => 'nullable|numeric|min:0',
            'quality_score' => 'nullable|numeric|min:1|max:10',
            'harvest_date' => 'nullable|date',
            'processing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'in:available,reserved,sold,expired',
        ]);

        $product->update($request->all());

        return redirect()->route('processor.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->user_id != Auth::id()) {
            abort(403);
        }

        $product->delete();

        return redirect()->route('processor.products.index')->with('success', 'Product deleted successfully.');
    }
}