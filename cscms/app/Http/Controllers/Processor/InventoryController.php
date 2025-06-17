<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index'); // Require authentication for all actions except index
    }

    public function index()
    {
        $userId = auth()->check() ? auth()->id() : null; // Null for unauthenticated users
        $raw_materials = $userId 
            ? Product::where('user_id', $userId)->where('product_type', 'green_beans')->get() 
            : collect(); // Raw materials are green beans
        $finished_goods = $userId 
            ? Product::where('user_id', $userId)->whereIn('product_type', ['roasted_beans', 'ground_coffee'])->get() 
            : collect(); // Finished goods are roasted or ground coffee

        Log::info('Inventory Index', [
            'user_id' => $userId,
            'raw_materials_count' => $raw_materials->count(),
            'finished_goods_count' => $finished_goods->count()
        ]);

        return view('processor.inventory.index', compact('raw_materials', 'finished_goods'));
    }

    public function create()
    {
        return view('processor.inventory.create');
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

        Product::create(array_merge($request->all(), ['user_id' => auth()->id() ?? 0]));

        return redirect()->route('processor.inventory.index')->with('success', 'Product added to inventory.');
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product); // Use Laravel's authorization policy
        return view('processor.inventory.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product); // Use Laravel's authorization policy
        return view('processor.inventory.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product); // Use Laravel's authorization policy

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

        return redirect()->route('processor.inventory.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product); // Use Laravel's authorization policy

        $product->delete();

        return redirect()->route('processor.inventory.index')->with('success', 'Product deleted successfully.');
    }
}