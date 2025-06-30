<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetailerInventoryController extends Controller
{
    public function index()
    {
        // Fetch inventory grouped by coffee breed and roast grade
        $inventory = DB::table('retailer_inventory')
            ->select('coffee_breed', 'roast_grade', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('coffee_breed', 'roast_grade')
            ->get();

        // Fetch inventory transactions ordered by date desc
        $transactions = DB::table('retailer_inventory')
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch ordered products from retailer_order
        $orderedProducts = DB::table('retailer_order')
            ->select('coffee_breed', 'roast_grade', DB::raw('SUM(quantity) as total_ordered'))
            ->groupBy('coffee_breed', 'roast_grade')
            ->get();

        // Fetch sales data joined with product_composition to compute coffee used
        $salesData = DB::table('retailer_sales as rs')
            ->join('retailer_products as rp', 'rs.product_id', '=', 'rp.product_id')
            ->join('product_composition as pc', 'rp.product_id', '=', 'pc.product_id')
            ->select(
                'rp.product_id as product_id',
                'rp.product_name as product_name',
                'pc.coffee_breed',
                'pc.roast_grade',
                DB::raw('SUM(rs.quantity * (pc.percentage / 100) * 0.075) as total_coffee_used')
            )
            ->groupBy('rp.product_id', 'rp.product_name', 'pc.coffee_breed', 'pc.roast_grade')
            ->get();

        // Compute remaining stock by subtracting coffee used from inventory
        $remainingStock = [];
        foreach ($inventory as $inv) {
            $used = 0;
            foreach ($salesData as $sale) {
                if ($sale->coffee_breed === $inv->coffee_breed && $sale->roast_grade == $inv->roast_grade) {
                    $used = $sale->total_coffee_used;
                    break;
                }
            }
            $remainingStock[] = [
                'coffee_breed' => $inv->coffee_breed,
                'roast_grade' => $inv->roast_grade,
                'total_quantity' => $inv->total_quantity,
                'remaining_quantity' => max(0, $inv->total_quantity - $used),
            ];
        }

        return view('retailers.inventory.index', compact('inventory', 'transactions', 'orderedProducts', 'salesData', 'remainingStock'));
    }
}
