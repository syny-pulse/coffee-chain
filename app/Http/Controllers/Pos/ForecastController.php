<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\InvoiceDetail;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use DateTime;





class ForecastController extends Controller
{
    public function forecastOverview(){
        return view('backend.forecast.forecast_overview');
    }// end method

    public function ForecastTrend(){
        $products = Product::all();

        return view('backend.forecast.forecast_trend', compact('products'));
    }// end method

    public function trendData(Request $request){
        // Determine date range based on trend type
        $startDate = null;
        $endDate = Carbon::today()->format('Y-m-d');

        switch ($request->trendType) {
            case 'lastweek':
                $startDate = Carbon::today()->subWeek()->format('Y-m-d');
                break;
            case 'lastmonth':
                $startDate = Carbon::today()->subMonth()->format('Y-m-d');
                break;
            case 'last6months':
                $startDate = Carbon::today()->subMonths(6)->format('Y-m-d');
                break;
            case 'lastyear':
                $startDate = Carbon::today()->subYear()->format('Y-m-d');
                break;
            case 'custom':
                $startDate = $request->startDate;
                $endDate = $request->endDate;
                break;
        }

        // Fetch sales data
        $salesData = DB::table('invoice_details')
            ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->join('products', 'invoice_details.product_id', '=', 'products.id')
            ->when($request->product, function ($query, $productId) {
                return $query->where('invoice_details.product_id', $productId);
            })
            ->whereBetween('invoices.date', [$startDate, $endDate])
            ->selectRaw('DATE(invoices.date) as date, SUM(invoice_details.selling_qty) as total_qty')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare data for Chart.js
        $labels = $salesData->pluck('date')->toArray();
        $data = $salesData->pluck('total_qty')->toArray();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);


    }//end method


    public function ForecastDemand(){
     $products = Product::all();
     return view('backend.forecast.forecast_demand', compact('products'));
    }//end method

    public function ForecastdemandData(Request $request){

            // Fetch historical sales data
        $salesData = DB::table('invoice_details')
        ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
        ->join('products', 'invoice_details.product_id', '=', 'products.id')
        ->when($request->product, function ($query, $productId) {
            return $query->where('invoice_details.product_id', $productId);
        })
        ->selectRaw('DATE(invoices.date) as date, SUM(invoice_details.selling_qty) as total_qty')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
        // Apply forecasting algorithm
        $forecastData = $this->applyForecastAlgorithm($salesData, $request->forecastPeriod, $request->algorithm);

        // Prepare response
        $labels = $forecastData->pluck('date')->toArray();
        $data = $forecastData->pluck('predicted_qty')->toArray();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);

    }//end method

    private function applyForecastAlgorithm($salesData, $forecastPeriod, $algorithm)
    {
        $forecastData = collect();

        switch ($algorithm) {
            case 'moving_average':
                $forecastData = $this->calculateMovingAverage($salesData, $forecastPeriod);
                break;

            case 'exponential_smoothing':
                $forecastData = $this->calculateExponentialSmoothing($salesData, $forecastPeriod);
                break;

            case 'linear_regression':
                $forecastData = $this->calculateLinearRegression($salesData, $forecastPeriod);
                break;
        }

        return $forecastData;
    }

    private function calculateMovingAverage($salesData, $period)
    {
        $windowSize = 3; // Adjust as needed
        $forecastData = collect();

        for ($i = $windowSize; $i < count($salesData); $i++) {
            $sum = 0;
            for ($j = $i - $windowSize; $j < $i; $j++) {
                $sum += $salesData[$j]->total_qty;
            }
            $forecastData->push([
                'date' => $salesData[$i]->date,
                'predicted_qty' => $sum / $windowSize,
            ]);
        }

        return $forecastData;
    }

    private function calculateExponentialSmoothing($salesData, $period)
    {
        $alpha = 0.3; // Smoothing factor (adjust as needed)
        $forecastData = collect();
        $previousForecast = $salesData[0]->total_qty;

        foreach ($salesData as $sale) {
            $predictedQty = $alpha * $sale->total_qty + (1 - $alpha) * $previousForecast;
            $forecastData->push([
                'date' => $sale->date,
                'predicted_qty' => $predictedQty,
            ]);
            $previousForecast = $predictedQty;
        }

        return $forecastData;
    }//end method

    private function calculateLinearRegression($salesData, $period)
    {
        // Simple linear regression implementation
        $n = count($salesData);
        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;

        foreach ($salesData as $index => $sale) {
            $sumX += $index;
            $sumY += $sale->total_qty;
            $sumXY += $index * $sale->total_qty;
            $sumX2 += $index * $index;
        }

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;

        $forecastData = collect();
        foreach ($salesData as $index => $sale) {
            $predictedQty = $slope * $index + $intercept;
            $forecastData->push([
                'date' => $sale->date,
                'predicted_qty' => $predictedQty,
            ]);
        }

        return $forecastData;
    }//end method


    public function daily(){
        $products = Product::all();
        return view('backend.forecast.forecast_daily', compact('products'));
    }

    public function dailyData(Request $request){
        // Fetch and process data for daily forecast
        $forecastData = []; // Replace with actual forecast logic
        return view('partials.daily_forecast_results', compact('forecastData'));
    }

    public function weekly(){
        $products = Product::all();
        return view('backend.forecast.forecast_weekly', compact('products'));
    }

    public function weeklyData(Request $request){

            // Fetch historical sales data grouped by week
            // $salesData = InvoiceDetail::where('product_id', $request->product)
            //     ->whereBetween('date', [$request->startDate, $request->endDate])
            //     ->selectRaw('YEARWEEK(date) as week, SUM(selling_qty) as total_qty')
            //     ->groupBy('week')
            //     ->orderBy('week')
            //     ->get();
            $salesData = DB::table('invoice_details')
            ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->join('products', 'invoice_details.product_id', '=', 'products.id')
            ->where('invoice_details.product_id', $request->product)
            ->whereBetween('invoices.date', [$request->startDate, $request->endDate])
            ->selectRaw('YEARWEEK(invoices.date) as week, SUM(invoice_details.selling_qty) as total_qty, products.name as product_name')
            ->groupBy('week','products.name')
            ->orderBy('week')
            ->get();



             // Calculate expected sales for next week
            $totalWeeks = count($salesData);
            $averageSales = $totalWeeks > 0 ? array_sum($salesData->pluck('total_qty')->toArray()) / $totalWeeks : 0;
            $expectedNextWeekSales = round($averageSales);

            // Fetch current stock for the selected product
            $currentStock = DB::table('products')->where('id', $request->product)->value('quantity');

            // Calculate stock needed for next week
            $totalStockNeeded = max(0, $expectedNextWeekSales - $currentStock);


            // Prepare data for TensorFlow.js
            $labels = [];
            $data = [];
            foreach ($salesData as $sale) {
                $labels[] = 'Week ' . $sale->week;
                $data[] = $sale->total_qty;
            }

            // Return JSON response for Chart.js and table
            return response()->json([
                'labels' => $labels,
                'data' => $data,

                'table' => view('backend.forecast.weekly_forecast_results', compact('salesData'))->render(),
                'summary' => view('backend.forecast.weekly_forecast_summary', compact('expectedNextWeekSales', 'currentStock', 'totalStockNeeded'))->render(),

            ]);
    }//end method

    public function monthly(){
     $products = Product::all();
        return view('backend.forecast.forecast_monthly', compact('products'));
    }

    public function monthlyData(Request $request){
                    $salesData = DB::table('invoice_details')
                ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
                ->join('products', 'invoice_details.product_id', '=', 'products.id')
                ->where('invoice_details.product_id', $request->product)
                ->whereBetween('invoices.date', [$request->startDate, $request->endDate])
                ->selectRaw('DATE_FORMAT(invoices.date, "%Y-%m") as month, SUM(invoice_details.selling_qty) as total_qty, products.name as product_name')
                ->groupBy('month', 'products.name')
                ->orderBy('month')
                ->get();

            // Calculate expected sales for next month
            $totalMonths = count($salesData);
            $averageSales = $totalMonths > 0 ? array_sum($salesData->pluck('total_qty')->toArray()) / $totalMonths : 0;
            $expectedNextMonthSales = round($averageSales);

            // Fetch current stock for the selected product
            $currentStock = DB::table('products')->where('id', $request->product)->value('quantity');

            // Calculate stock needed for next month
            $totalStockNeeded = max(0, $expectedNextMonthSales - $currentStock);

            // Prepare data for TensorFlow.js
            $labels = [];
            $data = [];
            foreach ($salesData as $sale) {
                $labels[] = 'Month ' . $sale->month; // Format: "Month YYYY-MM"
                $data[] = $sale->total_qty;
            }

            // Return JSON response for Chart.js and table
            return response()->json([
                'labels' => $labels,
                'data' => $data,
                'table' => view('backend.forecast.monthly_forecast_results', compact('salesData'))->render(),
                'summary' => view('backend.forecast.monthly_forecast_summary', compact('expectedNextMonthSales', 'currentStock', 'totalStockNeeded'))->render(),
            ]);

    }//end method



    // Fetch product sales trend data
    public function getProductTrendData(Request $request){
        // Get filters from the request
        $trendType = $request->input('trendType');
        $productId = $request->input('product');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');


        // Base query
        $query = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id') // Join invoice_details
            ->join('products', 'invoice_details.product_id', '=', 'products.id') // Join products
            ->where('invoices.status', 1) // Filter by invoice status
            ->whereIn('payments.paid_status', ['full_paid', 'partial_paid']) // Filter by payment status
            ->groupBy(DB::raw('DATE(invoices.date)')) // Group by date
            ->orderBy('date') // Order by date
            ->selectRaw('DATE(invoices.date) as date, SUM(payments.paid_amount) as total_revenue');

        // Apply product filter (if applicable)
        if ($productId) {
            $query->where('products.id', $productId); // Filter by product ID
        }

        // Apply date range filter
        if ($trendType === 'custom' && $startDate && $endDate) {
            $query->whereBetween('invoices.date', [$startDate, $endDate]); // Custom date range
        } else {
            // Apply predefined date ranges
            $dateRange = $this->getDateRange($trendType);
            $query->whereBetween('invoices.date', [$dateRange['start'], $dateRange['end']]); // Predefined range
        }

        // Fetch data
        $data = $query->get();
        // return response($data);


        // Format data for Chart.js
        $labels = $data->pluck('date')->toArray();
        $revenue = $data->pluck('total_revenue')->toArray();

        return response()->json([
            'labels' => $labels,
            'data' => $revenue,
        ]);
    }// end method


     // Fetch yearly/monthly/daily sales trend data
     public function getYearlyTrendData(Request $request){
    $trendType = $request->input('trendType');
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');

  // Base query
$query = DB::table('invoices')
->join('payments', 'invoices.id', '=', 'payments.invoice_id')
->selectRaw('MONTH(invoices.date) as month, SUM(payments.paid_amount) as total_sales')
->where('invoices.status', 1)
->whereIn('payments.paid_status', ['full_paid', 'partial_paid']);

// Apply date range filter
if ($trendType === 'custom' && $startDate && $endDate) {
$query->whereBetween('invoices.date', [$startDate, $endDate]);
} else {
// Apply predefined date ranges
$dateRange = $this->getDateRange($trendType);
$query->whereBetween('invoices.date', [$dateRange['start'], $dateRange['end']]);
}

// Fetch data grouped by month
$data = $query->groupByRaw('MONTH(invoices.date)')
->orderBy('month')
->get();

// return response($data);



    // Format data for Chart.js
    $labels = $data->pluck('month')->map(function ($month) {
        return DateTime::createFromFormat('!m', $month)->format('F'); // Convert month number to name
    })->toArray();



    $revenue = $data->pluck('total_sales')->toArray();



    return response()->json([
        'labels' => $labels,
        'data' => $revenue,
    ]);
}

     // Helper function to get date ranges
     private function getDateRange($trendType)
     {
         $now = Carbon::now();

         switch ($trendType) {
             case 'lastweek':
                 return [
                     'start' => $now->copy()->subWeek()->startOfWeek(),
                     'end' => $now->copy()->subWeek()->endOfWeek(),
                 ];
             case 'lastmonth':
                 return [
                     'start' => $now->copy()->subMonth()->startOfMonth(),
                     'end' => $now->copy()->subMonth()->endOfMonth(),
                 ];
             case 'last6months':
                 return [
                     'start' => $now->copy()->subMonths(6)->startOfMonth(),
                     'end' => $now->copy()->endOfMonth(),
                 ];
             case 'lastyear':
                 return [
                     'start' => $now->copy()->subYear()->startOfYear(),
                     'end' => $now->copy()->subYear()->endOfYear(),
                 ];
             default:
                 return [
                     'start' => $now->copy()->subMonth()->startOfMonth(),
                     'end' => $now->copy()->endOfMonth(),
                 ];
         }
     }


}
