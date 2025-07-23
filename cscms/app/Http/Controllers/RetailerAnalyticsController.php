<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RetailerAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date') ?: now()->subDays(7)->toDateString();
        $end = $request->input('end_date') ?: now()->toDateString();
        $product = $request->input('product');
        $salesQuery = DB::table('retailer_sales as rs')
            ->join('retailer_products as rp', 'rs.product_id', '=', 'rp.product_id')
            ->select('rp.name as product_name', DB::raw('SUM(rs.quantity) as total_sold'))
            ->whereBetween('rs.date', [$start, $end]);
        if ($product) {
            $salesQuery->where('rp.name', $product);
        }
        $sales = $salesQuery->groupBy('rp.name')->orderByDesc('total_sold')->get();
        $incomeQuery = DB::table('retailer_sales as rs')
            ->join('retailer_products as rp', 'rs.product_id', '=', 'rp.product_id')
            ->whereBetween('rs.date', [$start, $end]);
        if ($product) {
            $incomeQuery->where('rp.name', $product);
        }
        $income = $incomeQuery->sum(DB::raw('rs.quantity * rp.price_per_kg'));
        $salesByDayQuery = DB::table('retailer_sales')
            ->select('date', DB::raw('SUM(quantity) as total_sold'))
            ->whereBetween('date', [$start, $end]);
        if ($product) {
            $salesByDayQuery->whereIn('product_id', function($q) use ($product) {
                $q->select('product_id')->from('retailer_products')->where('name', $product);
            });
        }
        $salesByDay = $salesByDayQuery->groupBy('date')->orderBy('date')->get();
        // Advanced analytics
        $topProducts = DB::table('retailer_sales as rs')
            ->join('retailer_products as rp', 'rs.product_id', '=', 'rp.product_id')
            ->select('rp.name as product_name', DB::raw('SUM(rs.quantity) as total_sold'))
            ->groupBy('rp.name')->orderByDesc('total_sold')->limit(5)->get();
        $salesTrends = DB::table('retailer_sales as rs')
            ->join('retailer_products as rp', 'rs.product_id', '=', 'rp.product_id')
            ->select(DB::raw('DATE_FORMAT(rs.date, "%Y-%m") as month'), DB::raw('SUM(rs.quantity) as total_sold'))
            ->where('rs.date', '>=', now()->subMonths(12))
            ->groupBy('month')->orderBy('month')->get();
        $turnover = DB::table('retailer_sales as rs')
            ->join('retailer_inventory as ri', 'rs.product_id', '=', 'ri.product_id')
            ->where('rs.date', '>=', now()->subDays(30))
            ->select(DB::raw('SUM(rs.quantity) / NULLIF(SUM(ri.quantity),0) as turnover_rate'))
            ->value('turnover_rate');
        return view('retailers.analytics.index', compact('sales', 'income', 'salesByDay', 'topProducts', 'salesTrends', 'turnover'));
    }

    public function export(Request $request, $type)
    {
        $start = $request->input('start_date') ?: now()->subDays(7)->toDateString();
        $end = $request->input('end_date') ?: now()->toDateString();
        $product = $request->input('product');
        if ($type === 'csv') {
            $salesQuery = DB::table('retailer_sales as rs')
                ->join('retailer_products as rp', 'rs.product_id', '=', 'rp.product_id')
                ->select('rp.name as product_name', DB::raw('SUM(rs.quantity) as total_sold'))
                ->whereBetween('rs.date', [$start, $end]);
            if ($product) {
                $salesQuery->where('rp.name', $product);
            }
            $sales = $salesQuery->groupBy('rp.name')->orderByDesc('total_sold')->get();
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

    public function demandPlanning(Request $request)
    {
        $month = $request->input('month') ?: now()->month;
        $year = $request->input('year') ?: now()->year;
        $products = DB::table('retailer_products')->select('product_id', 'name')->get();
        $mlServerUrl = config('services.ml_server.url', 'http://localhost:5000');
        $predictions = [];
        $mlError = false;
        foreach ($products as $product) {
            try {
                $response = Http::get("{$mlServerUrl}/api/predict-demand", [
                    'product_name' => $product->name,
                    'month' => $month,
                    'year' => $year,
                ]);
                if ($response->successful() && $response->json('predicted_demand')) {
                    $predictions[$product->product_id] = $response->json('predicted_demand');
                } else {
                    $predictions[$product->product_id] = rand(500, 2000); // fallback
                    $mlError = true;
                }
            } catch (\Exception $e) {
                $predictions[$product->product_id] = rand(500, 2000); // fallback
                $mlError = true;
            }
        }
        $inventory = DB::table('retailer_inventory')
            ->select('product_id', 'quantity')
            ->whereIn('product_id', $products->pluck('product_id'))
            ->get()->keyBy('product_id');
        return view('retailers.demand_planning.index', [
            'products' => $products,
            'predictions' => $predictions,
            'inventory' => $inventory,
            'month' => $month,
            'year' => $year,
            'mlError' => $mlError,
        ]);
    }
} 