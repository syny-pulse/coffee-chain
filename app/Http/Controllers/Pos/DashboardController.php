<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{


    public function Dashboard(Request $request){
        $filter = $request->input('filter', 'Last 6Month');
        $startDate = null;
        $endDate = null;
        $labels = [];
        $salesData = [];
        $purchasesData = [];

        // Determine the date range based on the filter
        switch ($filter) {
            case 'Last Week':
                $startDate = Carbon::now()->subWeek()->startOfWeek();
                $endDate = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'This Month':
                $startDate = Carbon::now()->startOfMonth(); // Use Carbon::now() for current month
                $endDate = Carbon::now()->endOfMonth();

                break;
            case 'Last Month':
                $startDate = Carbon::now()->subMonth()->startOfMonth(); // Use Carbon::now() for last month
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'Last 6Month':
                $startDate = Carbon::now()->subMonths(6)->startOfMonth()->format('Y-m-d'); // Format the start date
                $endDate = Carbon::now()->endOfMonth()->format('Y-m-d'); // Format the end date
                break;
                case 'Last Year':
                    $startDate = Carbon::now()->subYear()->startOfYear()->format('Y-m-d'); // Start of last year
                    $endDate = Carbon::now()->subYear()->endOfYear()->format('Y-m-d'); // End of last year
                    break;

            case 'Fiscal':
                // Example fiscal year (assume starts in July)
                $startDate = Carbon::now()->month >= 7 ? Carbon::now()->startOfYear()->addMonths(6) : Carbon::now()->startOfYear()->subMonths(6);
                $endDate = Carbon::now()->month >= 7 ? Carbon::now()->endOfYear() : Carbon::now()->subMonths(6)->endOfMonth();
                break;
            case 'Custom date':
                // Handle custom date range (if provided in request)
                $startDate = Carbon::parse($request->input('start_date')); // Parse custom date
                $endDate = Carbon::parse($request->input('end_date'));   // Parse custom date
                break;
            default: // "This Year"
                $startDate = Carbon::now()->startOfYear(); // Use Carbon::now() for the start of the year
                $endDate = Carbon::now()->endOfYear();     // Use Carbon::now() for the end of the year
                break;
        }

        // Query for sales and purchases data
        $sales = InvoiceDetail::join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
            ->selectRaw('MONTH(invoice_details.date) as month, SUM(payments.paid_amount) as total_sales')
            ->where('invoices.status', 1)
            ->whereIn('payments.paid_status', ['full_paid', 'partial_paid'])
            ->whereBetween('invoice_details.date', [$startDate, $endDate])
            ->groupByRaw('MONTH(invoice_details.date)')
            ->get()
            ->pluck('total_sales', 'month');

        $purchases = Purchase::selectRaw('MONTH(date) as month, SUM(buying_price) as total_purchases')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 1)
            ->groupBy('month')
            ->get()
            ->pluck('total_purchases', 'month');


            if ($filter == 'This Week' || $filter == 'Last Week') {
                $startDate = Carbon::now()->startOfWeek();  // Start of the week (for "This Week")
                $endDate = Carbon::now()->endOfWeek();      // End of the week (for "This Week")

                // If it's "Last Week"
                if ($filter == 'Last Week') {
                    $startDate = Carbon::now()->subWeek()->startOfWeek();  // Start of last week
                    $endDate = Carbon::now()->subWeek()->endOfWeek();      // End of last week
                }

                // Loop through the days of the week
                $currentDate = $startDate;
                while ($currentDate <= $endDate) {
                    // Add the day name (e.g., Monday, Tuesday) to the labels array
                    $labels[] = $currentDate->format('l');  // 'l' gives the full textual day name

                    // Filter sales data for the day
                    $dailySales = $sales->filter(function ($sale) use ($currentDate) {
                        $saleDate = Carbon::parse($sale->month . '-' . $sale->day);
                        return $saleDate->isSameDay($currentDate);
                    });
                    $salesData[] = $dailySales->sum('total_sales');

                    // Filter purchase data for the day
                    $dailyPurchases = $purchases->filter(function ($purchase) use ($currentDate) {
                        $purchaseDate = Carbon::parse($purchase->month . '-' . $purchase->day);
                        return $purchaseDate->isSameDay($currentDate);
                    });
                    $purchasesData[] = $dailyPurchases->sum('total_purchases');

                    // Move to the next day
                    $currentDate->addDay();
                }
            }
        else if ($filter == 'This Month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            $currentDate = $startDate;
            $weekIndex = 1;

            while ($currentDate <= $endDate) {
                $weekStart = $currentDate->copy()->startOfWeek();
                $weekEnd = $currentDate->copy()->endOfWeek();

                // Format week labels like "Week 1", "Week 2", etc.
                $labels[] = 'Week ' . $weekIndex;

                // Filter sales data for the week
                $weeklySales = $sales->filter(function ($sale) use ($weekStart, $weekEnd) {
                    $saleDate = Carbon::parse($sale->month . '-' . $sale->day);
                    return $saleDate->between($weekStart, $weekEnd);
                });
                $salesData[] = $weeklySales->sum('total_sales');

                // Filter purchase data for the week
                $weeklyPurchases = $purchases->filter(function ($purchase) use ($weekStart, $weekEnd) {
                    $purchaseDate = Carbon::parse($purchase->month . '-' . $purchase->day);
                    return $purchaseDate->between($weekStart, $weekEnd);
                });
                $purchasesData[] = $weeklyPurchases->sum('total_purchases');

                // Move to the next week
                $currentDate->addWeek();
                $weekIndex++;
            }
        }else {
            // Get the correct range of months dynamically based on filter (Last 6 Month or others)
            if ($filter == 'Last 6Month') {
                $monthsRange = range(5, 0);
            } elseif ($filter == 'This Year' || $filter == 'Last Year') {
                $monthsRange = range(12, 1);
            } elseif ($filter == 'Last Year') {
                $monthsRange = range(11, 0); // All months of the previous year
            }

            // Get sales and purchases data for the dynamic range of months
            foreach ($monthsRange as $i) {
                if ($filter == 'Last Year') {
                    $date = Carbon::now()->subYear()->startOfYear()->addMonths($i);
                } else {
                    $date = Carbon::now()->subMonths($i);
                }
                $month = Carbon::now()->subMonths($i)->format('F');
                $labels[] = $month;
                $salesData[] = $sales[Carbon::now()->subMonths($i)->month] ?? 0;
                $purchasesData[] = $purchases[Carbon::now()->subMonths($i)->month] ?? 0;
            }
        }

        // Return JSON response for AJAX
        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'sales' => $salesData,
                'purchases' => $purchasesData,
            ]);
        }

        // Return view when not AJAX request
        return view('admin.index', [
            'labels' => $labels,
            'sales' => $salesData,
            'purchases' => $purchasesData,
        ]);
    }


    public function getTotalSales(Request $request)
    {
        $dateRange = $request->date_range;
        $startDate = $request->start_date;
        $endDate = $request->end_date;


        // Get the total sales based on the selected date range
        $totalSales = 0;

        switch ($dateRange) {
            case 'Today':

                $totalSales = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                // ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id') // Join invoice details table
                ->where('invoices.status', 1) // Filter invoices with status 1
                ->whereIn('payments.paid_status', ['full_paid', 'partial_paid']) // Include specified paid statuses
                ->whereDate('invoices.date', Carbon::today()) // Filter by today's date
                ->groupBy('payments.invoice_id') // Group by invoice to prevent duplicate summation
                ->selectRaw('SUM(payments.paid_amount) as total_paid') // Calculate the sum of paid_amount
                ->pluck('total_paid') // Retrieve the grouped totals as an array
                ->sum(); // Sum all grouped totals
                break;

            case 'This Week':

                $totalSales = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                    ->where('invoices.status', 1) // Filter invoices with status 1
                    ->whereIn('payments.paid_status', ['full_paid', 'partial_paid']) // Include specified paid statuses
                    ->whereBetween('invoices.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]) // Filter by custom date range
                    ->groupBy('payments.invoice_id') // Group by invoice to prevent duplicate summation
                    ->selectRaw('SUM(payments.paid_amount) as total_paid') // Calculate the sum of paid_amount
                    ->pluck('total_paid') // Retrieve the grouped totals as an array
                    ->sum(); // Sum all grouped totals

                break;

                case 'Last Week':

                    $totalSales = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                        ->where('invoices.status', 1) // Filter invoices with status 1
                        ->whereIn('payments.paid_status', ['full_paid', 'partial_paid']) // Include specified paid statuses
                        ->whereBetween('invoices.date', [
                            Carbon::now()->subWeek()->startOfWeek(), // Start of last week
                            Carbon::now()->subWeek()->endOfWeek()    // End of last week
                        ]) // Filter by last week's date range
                        ->groupBy('payments.invoice_id') // Group by invoice ID to avoid duplication
                        ->selectRaw('SUM(payments.paid_amount) as total_paid') // Calculate the sum of paid amounts for each invoice
                        ->pluck('total_paid') // Retrieve the grouped totals as an array
                        ->sum(); // Sum all grouped totals
                    break;

            case 'This Month':

                $totalSales = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                ->where('invoices.status', 1) // Filter invoices with status 1
                ->whereIn('payments.paid_status', ['full_paid', 'partial_paid']) // Include specified paid statuses
                ->whereMonth('invoices.date', Carbon::now()->month) // Filter by the current month using invoices.date
                ->groupBy('payments.invoice_id') // Group by invoice to avoid duplicate summations
                ->selectRaw('SUM(payments.paid_amount) as total_paid') // Sum the paid amounts for each group
                ->pluck('total_paid') // Retrieve grouped totals as an array
                ->sum(); // Sum all grouped totals
                break;

            case 'Last Month':
                $sDate = Carbon::now()->subMonth()->startOfMonth()->toDateString(); // First day of last month
                $eDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();

                $totalSales = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                ->where('invoices.status', 1) // Filter invoices with status 1
                ->whereIn('payments.paid_status', ['full_paid', 'partial_paid']) // Include specified paid statuses
                ->whereBetween('invoices.date', [$sDate, $eDate]) // Filter by custom date range
                ->groupBy('payments.invoice_id') // Group by invoice to avoid duplicate summations
                ->selectRaw('SUM(payments.paid_amount) as total_paid') // Sum the paid amounts for each group
                ->pluck('total_paid') // Retrieve grouped totals as an array
                ->sum(); // Sum all grouped totals
                break;

            case 'Custom Date':
                if ($startDate && $endDate) {


                $totalSales = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                ->where('invoices.status', 1) // Filter invoices with status 1
                ->whereIn('payments.paid_status', ['full_paid', 'partial_paid']) // Include specified paid statuses
                ->whereBetween('invoices.date', [$startDate, $endDate]) // Filter by custom date range
                ->groupBy('payments.invoice_id') // Group by invoice to avoid duplicate summations
                ->selectRaw('SUM(payments.paid_amount) as total_paid') // Sum the paid amounts for each group
                ->pluck('total_paid') // Retrieve grouped totals as an array
                ->sum(); // Sum all grouped totals

                }
                break;
            default:
                $totalSales = 0;
                break;
        }

        // Return the total sales as a JSON response
        return response()->json(['totalSales' => number_format($totalSales, 0)]);
    }


    // ------------------------balance -------------------
    public function getOutstandingBalance(Request $request)
    {
        $dateRange = $request->date_range;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Get the total outstanding balance based on the selected date range
        $outstandingBalance = 0;

        switch ($dateRange) {
            case 'Today':


                $outstandingBalance = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                ->where('invoices.status', 1) // Filter invoices with status 1
                ->whereIn('payments.paid_status', ['full_paid', 'partial_paid','full_due']) // Consider only paid or partially paid invoices
                ->whereDate('invoices.date', Carbon::today()) // Filter by today's date
                ->selectRaw('SUM(payments.due_amount) as total_due') // Sum the due_amount
                ->groupBy('payments.invoice_id') // Group by invoice_id to avoid duplication
                ->pluck('total_due') // Fetch the total_due values
                ->sum(); // Calculate the final sum

                break;

            case 'This Week':
                // $outstandingBalance = InvoiceDetail::join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
                //     ->join('payments', 'invoices.id', '=', 'payments.invoice_id') // Join payments table
                //     ->where('invoices.status', 1) // Filter invoices with status 1
                //     ->whereIn('payments.paid_status', ['full_paid', 'partial_paid']) // Consider only paid or partially paid invoices
                //     ->whereBetween('invoice_details.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]) // Filter by date range
                //     ->sum('payments.due_amount'); // Sum the paid amounts from payments table

                $outstandingBalance = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                    ->where('invoices.status', 1) // Filter invoices with status 1
                    ->whereIn('payments.paid_status', ['full_paid', 'partial_paid','full_due']) // Consider only fully or partially paid invoices
                    ->whereBetween('invoices.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]) // Filter by date range (current week)
                    ->selectRaw('SUM(payments.due_amount) as total_due') // Calculate the sum of due_amount
                    ->groupBy('payments.invoice_id') // Group by invoice_id to avoid duplicates
                    ->pluck('total_due') // Get the grouped total_due values
                    ->sum(); // Sum all grouped values

                break;

            case 'Last Week':
                // $outstandingBalance = InvoiceDetail::join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
                //     ->join('payments', 'invoices.id', '=', 'payments.invoice_id') // Join payments table
                //     ->where('invoices.status', 1) // Filter invoices with status 1
                //     ->whereIn('payments.paid_status', ['full_paid', 'partial_paid']) // Consider only paid or partially paid invoices
                //     ->whereBetween('invoice_details.date', [
                //         Carbon::now()->subWeek()->startOfWeek(),
                //         Carbon::now()->subWeek()->endOfWeek()
                //     ]) // Filter by last week's date range
                //     ->sum('payments.due_amount'); // Sum the paid amounts from payments table
                $outstandingBalance = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                    ->where('invoices.status', 1) // Filter invoices with status 1
                    ->whereIn('payments.paid_status', ['full_paid', 'partial_paid','full_due']) // Consider fully or partially paid invoices
                    ->whereBetween('invoices.date', [
                        Carbon::now()->subWeek()->startOfWeek(),
                        Carbon::now()->subWeek()->endOfWeek()
                    ]) // Filter by last week's date range
                    ->selectRaw('SUM(payments.due_amount) as total_due') // Calculate the sum of due_amount
                    ->groupBy('payments.invoice_id') // Group by invoice_id to avoid duplicates
                    ->pluck('total_due') // Get the grouped totals as an array
                    ->sum(); // Sum all grouped values

                break;

            case 'This Month':
                // $outstandingBalance = InvoiceDetail::join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
                //     ->join('payments', 'invoices.id', '=', 'payments.invoice_id') // Join payments table
                //     ->where('invoices.status', 1) // Filter invoices with status 1
                //     ->whereIn('payments.paid_status', ['full_paid', 'partial_paid','full_due']) // Consider only paid or partially paid invoices
                //     ->whereMonth('invoice_details.date', Carbon::now()->month) // Filter by current month
                //     ->sum('payments.due_amount'); // Sum the paid amounts from payments table
                $outstandingBalance = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                ->where('invoices.status', 1) // Filter invoices with status 1
                ->whereIn('payments.paid_status', ['full_paid', 'partial_paid', 'full_due']) // Include all specified paid statuses
                ->whereMonth('invoices.date', Carbon::now()->month) // Filter invoices by the current month
                ->selectRaw('SUM(payments.due_amount) as total_due') // Calculate the sum of due_amount
                ->groupBy('payments.invoice_id') // Group by invoice_id to avoid duplicates
                ->pluck('total_due') // Get the grouped totals as an array
                ->sum(); // Sum all grouped values

                break;







            case 'Last Month':
                $startDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
                $endDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();

                // $outstandingBalance = InvoiceDetail::join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
                //     ->join('payments', 'invoices.id', '=', 'payments.invoice_id') // Join payments table
                //     ->where('invoices.status', 1) // Filter invoices with status 1
                //     ->whereIn('payments.paid_status', ['full_paid', 'partial_paid','full_due']) // Consider only paid or partially paid invoices
                //     ->whereBetween('invoice_details.date', [$startDate, $endDate]) // Filter by last month's date range
                //     ->sum('payments.due_amount'); // Sum the paid amounts from payments table

                $outstandingBalance = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                ->where('invoices.status', 1) // Filter invoices with status 1
                ->whereIn('payments.paid_status', ['full_paid', 'partial_paid', 'full_due']) // Include all specified paid statuses
                ->whereBetween('invoices.date', [$startDate, $endDate]) // Filter invoices by the given date range
                ->selectRaw('SUM(payments.due_amount) as total_due') // Calculate the sum of due_amount
                ->groupBy('payments.invoice_id') // Group by invoice_id to avoid duplicates
                ->pluck('total_due') // Get the grouped totals as an array
                ->sum(); // Sum all grouped values

                break;

            case 'Custom Date1':
                if ($startDate && $endDate) {
                    // $outstandingBalance = InvoiceDetail::join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
                    //     ->join('payments', 'invoices.id', '=', 'payments.invoice_id') // Join payments table
                    //     ->where('invoices.status', 1) // Filter invoices with status 1
                    //     ->whereIn('payments.paid_status', ['full_paid', 'partial_paid']) // Consider only paid or partially paid invoices
                    //     ->whereBetween('invoice_details.date', [$startDate, $endDate]) // Filter by custom date range
                    //     ->sum('payments.due_amount'); // Sum the paid amounts from payments table
                    $outstandingBalance = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id') // Join invoices table
                    ->where('invoices.status', 1) // Filter invoices with status 1
                    ->whereIn('payments.paid_status', ['full_paid', 'partial_paid','full_due']) // Include specified paid statuses
                    ->whereBetween('invoices.date', [$startDate, $endDate]) // Filter by custom date range
                    ->groupBy('payments.invoice_id') // Group by invoice to prevent duplicate summation
                    ->selectRaw('SUM(payments.due_amount) as total_due') // Calculate the sum of due_amount
                    ->pluck('total_due') // Retrieve the grouped totals as an array
                    ->sum(); // Sum all grouped totals

                }
                break;

            default:
                $outstandingBalance = 0;
                break;
        }

        // Return the outstanding balance as a JSON response
        return response()->json(['outstandingBalance' => number_format($outstandingBalance, 0)]);
    }
}
