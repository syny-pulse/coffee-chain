<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RetailerInventory;

class RetailerSalesController extends Controller
{
    public function index(Request $request)
    {
        $products = \DB::table('retailer_products')->get();
        $query = \DB::table('retailer_sales')
            ->join('retailer_products', 'retailer_sales.product_id', '=', 'retailer_products.product_id')
            ->select('retailer_sales.*', 'retailer_products.name as product_name');
        // Filters
        if ($request->filled('date_from')) {
            $query->where('retailer_sales.date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('retailer_sales.date', '<=', $request->date_to);
        }
        if ($request->filled('product_id')) {
            $query->where('retailer_sales.product_id', $request->product_id);
        }
        $sales = $query->orderByDesc('retailer_sales.date')->orderByDesc('retailer_sales.id')->limit(30)->get();
        $inventory = \DB::table('retailer_inventory')->get()->keyBy('product_id');
        // Export CSV
        if ($request->get('export') === 'csv') {
            $csv = "date,product,quantity\n";
            foreach ($sales as $row) {
                $csv .= "{$row->date},{$row->product_name},{$row->quantity}\n";
            }
            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="sales.csv"',
            ]);
        }
        // Sales by day for chart
        $salesByDayQuery = \DB::table('retailer_sales')
            ->select('date', \DB::raw('SUM(quantity) as total_sold'));
        if ($request->filled('date_from')) {
            $salesByDayQuery->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $salesByDayQuery->where('date', '<=', $request->date_to);
        }
        if ($request->filled('product_id')) {
            $salesByDayQuery->where('product_id', $request->product_id);
        }
        $salesByDay = $salesByDayQuery->groupBy('date')->orderBy('date')->get();
        return view('retailers.sales.index', compact('products', 'sales', 'inventory', 'salesByDay'));
    }

    public function create()
    {
        $products = \DB::table('retailer_products')->get();
        return view('retailers.sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'product_id' => 'required|exists:retailer_products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Insert or update sale
        \DB::table('retailer_sales')->updateOrInsert(
            ['date' => $data['date'], 'product_id' => $data['product_id']],
            ['quantity' => $data['quantity'], 'updated_at' => now(), 'created_at' => now()]
        );

        // Decrement inventory for the sold product
        $product = \DB::table('retailer_products')->where('product_id', $data['product_id'])->first();
        if ($product) {
            $composition = \DB::table('product_composition')->where('product_id', $product->product_id)->first();
            if ($composition) {
                RetailerInventory::where('product_type', $product->variant ?? 'drinking_coffee')
                    ->where('coffee_breed', $composition->coffee_breed)
                    ->where('roast_grade', $composition->roast_grade)
                    ->decrement('quantity', $data['quantity']);
            }
        }

        return redirect()->route('retailer.sales.index')->with('success', 'Sale saved and inventory updated.');
    }
}
