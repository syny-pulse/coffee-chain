<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view inventory.');
        }

        $raw_materials = Product::where('user_id', $user->id)
            ->where('product_type', 'green_beans')
            ->get();

        $finished_goods = Product::where('user_id', $user->id)
            ->whereIn('product_type', ['roasted_beans', 'ground_coffee'])
            ->get();

        // Calculate additional metrics
        $total_processing_capacity = 1000; // Default capacity
        $ready_for_sale_count = $finished_goods->where('status', 'available')->count();

        Log::info('Inventory Index', [
            'user_id' => $user->id,
            'raw_materials_count' => $raw_materials->count(),
            'finished_goods_count' => $finished_goods->count()
        ]);

        return view('processor.inventory.index', compact(
            'raw_materials', 
            'finished_goods', 
            'total_processing_capacity', 
            'ready_for_sale_count'
        ));
    }

    public function create()
    {
        return view('processor.inventory.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to add inventory.');
        }

        $validated = $request->validate([
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
            'status' => 'required|in:available,reserved,sold,expired',
        ]);

        Product::create(array_merge(
            $validated,
            ['user_id' => $user->id]
        ));

        return redirect()->route('processor.inventory.index')->with('success', 'Product added to inventory.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $item = Inventory::where('id', $id)
            ->where('processor_company_id', $user->company_id)
            ->firstOrFail();

        return view('processor.inventory.show', compact('item'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $item = Inventory::where('id', $id)
            ->where('processor_company_id', $user->company_id)
            ->firstOrFail();

        return view('processor.inventory.edit', compact('item'));
    }

    public function update(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($product->user_id !== $user->id) {
            return abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
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
            'status' => 'required|in:available,reserved,sold,expired',
        ]);

        $product->update($validated);

        return redirect()->route('processor.inventory.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($product->user_id !== $user->id) {
            return abort(403, 'Unauthorized action.');
        }

        $product->delete();

        return redirect()->route('processor.inventory.index')->with('success', 'Product deleted successfully.');
    }
}