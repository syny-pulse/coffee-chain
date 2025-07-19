<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class RetailerAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Example: Sales by product for the last 7 days
        $sales = DB::table('retailer_sales as rs')
            ->join('retailer_products as rp', 'rs.product_id', '=', 'rp.product_id')
            ->select('rp.product_name', DB::raw('SUM(rs.quantity) as total_sold'))
            ->where('rs.date', '>=', now()->subDays(7))
            ->groupBy('rp.product_name')
            ->orderByDesc('total_sold')
            ->get();

        // Example: Total income for last 7 days
        $income = DB::table('retailer_sales as rs')
            ->join('retailer_products as rp', 'rs.product_id', '=', 'rp.product_id')
            ->where('rs.date', '>=', now()->subDays(7))
            ->sum(DB::raw('rs.quantity * rp.price'));

        // Example: Sales by day for chart
        $salesByDay = DB::table('retailer_sales')
            ->select('date', DB::raw('SUM(quantity) as total_sold'))
            ->where('date', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('retailers.analytics.index', compact('sales', 'income', 'salesByDay'));
    }

    public function export(Request $request, $type)
    {
        // $type: 'csv' or 'pdf'
        if ($type === 'csv') {
            $sales = DB::table('retailer_sales as rs')
                ->join('retailer_products as rp', 'rs.product_id', '=', 'rp.product_id')
                ->select('rp.product_name', DB::raw('SUM(rs.quantity) as total_sold'))
                ->where('rs.date', '>=', now()->subDays(7))
                ->groupBy('rp.product_name')
                ->orderByDesc('total_sold')
                ->get();
            $csv = "product_name,total_sold\n";
            foreach ($sales as $row) {
                $csv .= "{$row->product_name},{$row->total_sold}\n";
            }
            return Response::make($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="sales.csv"',
            ]);
        } elseif ($type === 'pdf') {
            // In real implementation, use a PDF package
            return Response::make('PDF export not implemented', 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="sales.pdf"',
            ]);
        }
        abort(404);
    }
} 