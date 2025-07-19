<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailerProduct;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RetailerProductInfoController extends Controller
{
    public function index()
    {
        // List all retailer products
        $products = RetailerProduct::all();
        return view('retailers.product_info.index', compact('products'));
    }

    public function create()
    {
        $productTypes = ['Espresso', 'Latte', 'Iced Latte', 'Black Coffee'];
        $processingMethods = ['Washed', 'Natural', 'Honey', 'Wet-Hulled'];
        $roastLevels = ['Light', 'Medium', 'Medium-Dark', 'Dark'];
        $statuses = ['active', 'inactive', 'reserved', 'sold', 'expired'];
        return view('retailers.product_info.create', compact('productTypes', 'processingMethods', 'roastLevels', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|string|max:255',
            'origin_country' => 'required|string|max:255',
            'processing_method' => 'required|string|max:255',
            'roast_level' => 'required|string|max:255',
            'quantity_kg' => 'required|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:0',
            'quality_score' => 'required|numeric|min:0|max:10',
            'harvest_date' => 'required|date',
            'processing_date' => 'required|date',
            'expiry_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|string|max:255',
        ]);
        $product = new RetailerProduct();
        $product->user_id = auth()->id();
        $product->name = $validated['name'];
        $product->product_type = $validated['product_type'];
        $product->origin_country = $validated['origin_country'];
        $product->processing_method = $validated['processing_method'];
        $product->roast_level = $validated['roast_level'];
        $product->quantity_kg = $validated['quantity_kg'];
        $product->price_per_kg = $validated['price_per_kg'];
        $product->quality_score = $validated['quality_score'];
        $product->harvest_date = $validated['harvest_date'];
        $product->processing_date = $validated['processing_date'];
        $product->expiry_date = $validated['expiry_date'];
        $product->description = $validated['description'] ?? null;
        $product->status = $validated['status'];
        $product->save();
        return redirect()->route('retailer.product_info.index')->with('success', 'Product created.');
    }

    public function edit($id)
    {
        $product = RetailerProduct::findOrFail($id);
        $productTypes = ['Espresso', 'Latte', 'Iced Latte', 'Black Coffee'];
        $processingMethods = ['Washed', 'Natural', 'Honey', 'Wet-Hulled'];
        $roastLevels = ['Light', 'Medium', 'Medium-Dark', 'Dark'];
        $statuses = ['active', 'inactive', 'reserved', 'sold', 'expired'];
        return view('retailers.product_info.edit', compact('product', 'productTypes', 'processingMethods', 'roastLevels', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|string|max:255',
            'origin_country' => 'required|string|max:255',
            'processing_method' => 'required|string|max:255',
            'roast_level' => 'required|string|max:255',
            'quantity_kg' => 'required|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:0',
            'quality_score' => 'required|numeric|min:0|max:10',
            'harvest_date' => 'required|date',
            'processing_date' => 'required|date',
            'expiry_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|string|max:255',
        ]);
        $product = RetailerProduct::findOrFail($id);
        $product->name = $validated['name'];
        $product->product_type = $validated['product_type'];
        $product->origin_country = $validated['origin_country'];
        $product->processing_method = $validated['processing_method'];
        $product->roast_level = $validated['roast_level'];
        $product->quantity_kg = $validated['quantity_kg'];
        $product->price_per_kg = $validated['price_per_kg'];
        $product->quality_score = $validated['quality_score'];
        $product->harvest_date = $validated['harvest_date'];
        $product->processing_date = $validated['processing_date'];
        $product->expiry_date = $validated['expiry_date'];
        $product->description = $validated['description'] ?? null;
        $product->status = $validated['status'];
        $product->save();
        return redirect()->route('retailer.product_info.index')->with('success', 'Product updated.');
    }

    public function destroy($id)
    {
        // TODO: Delete product
        return redirect()->route('retailer.product_info.index')->with('success', 'Product deleted.');
    }

    public function productAnalytics($id)
    {
        $product = RetailerProduct::findOrFail($id);
        $sales = DB::table('retailer_sales')->where('product_id', $id)->sum('quantity');
        $revenue = $sales * ($product->price_per_kg ?? 0);
        $reviews = []; // No product_reviews table exists
        return view('retailers.product_info.analytics', compact('product', 'sales', 'revenue', 'reviews'));
    }
} 