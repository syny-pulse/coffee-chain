<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        }

        return redirect()->back()->with('success', 'Sales data saved successfully.');
    }
}
