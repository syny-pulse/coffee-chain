<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailerInventory;
use Illuminate\Support\Facades\DB;

class RetailerInventoryController extends Controller
{
    public function index()
    {
        // Fetch all product types
        $productTypes = ['drinking_coffee', 'roasted_coffee', 'coffee_scents', 'coffee_soap'];

        // Fetch inventory grouped by product_type, coffee_breed, and roast_grade
        $inventory = RetailerInventory::select('product_type', 'coffee_breed', 'roast_grade', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_type', 'coffee_breed', 'roast_grade')
            ->get();

        // Fetch inventory transactions ordered by date desc
        $transactions = RetailerInventory::orderBy('created_at', 'desc')->get();

        // Fetch ordered products from retailer_order
        $orderedProducts = DB::table('retailer_order')
            ->select('product_type', 'coffee_breed', 'roast_grade', DB::raw('SUM(quantity) as total_ordered'))
            ->groupBy('product_type', 'coffee_breed', 'roast_grade')
            ->get();

        // Compute remaining stock by subtracting coffee used from inventory (simplified)
        $remainingStock = $inventory->map(function($inv) {
            return [
                'product_type' => $inv->product_type,
                'coffee_breed' => $inv->coffee_breed,
                'roast_grade' => $inv->roast_grade,
                'total_quantity' => $inv->total_quantity,
                'remaining_quantity' => $inv->total_quantity, // For now, not subtracting sales
            ];
        });

        return view('retailers.inventory.index', compact('inventory', 'transactions', 'orderedProducts', 'remainingStock', 'productTypes'));
    }

    public function storeAdjustment(Request $request)
    {
        $data = $request->validate([
            'product_type' => 'required|in:drinking_coffee,roasted_coffee,coffee_scents,coffee_soap',
            'coffee_breed' => 'required|in:arabica,robusta',
            'roast_grade' => 'required|in:Grade 1,Grade 2,Grade 3,Grade 4,Grade 5',
            'quantity' => 'required|integer',
            'reason' => 'nullable|string',
        ]);
        RetailerInventory::create($data);
        return redirect()->route('retailer.inventory.index')->with('success', 'Inventory adjusted successfully.');
    }
}
