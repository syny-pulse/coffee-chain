<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RetailerInventory;

class RetailerSalesController extends Controller
{
    public function index()
    {
        // Get all products for sales entry
        $products = DB::table('retailer_products')->get();

        return view('retailers.sales.index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'sales' => 'required|array',
            'sales.*.product_id' => 'required|exists:retailer_products,product_id',
            'sales.*.quantity' => 'required|integer|min:0',
        ]);

        foreach ($data['sales'] as $sale) {
            DB::table('retailer_sales')->updateOrInsert(
                ['date' => $data['date'], 'product_id' => $sale['product_id']],
                ['quantity' => $sale['quantity'], 'updated_at' => now(), 'created_at' => now()]
            );

            // Decrement inventory for the sold product
            $product = DB::table('retailer_products')->where('product_id', $sale['product_id'])->first();
            if ($product) {
                // For simplicity, assume product_type, coffee_breed, roast_grade are in product or composition
                $composition = DB::table('product_composition')->where('product_id', $product->product_id)->first();
                if ($composition) {
                    RetailerInventory::where('product_type', $product->variant ?? 'drinking_coffee')
                        ->where('coffee_breed', $composition->coffee_breed)
                        ->where('roast_grade', $composition->roast_grade)
                        ->decrement('quantity', $sale['quantity']);
                }
            }
        }

        return redirect()->back()->with('success', 'Sales data saved successfully.');
    }
}
