<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FarmerOrder;
use App\Models\Pricing;

class FinancialController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        $orders = FarmerOrder::where('farmer_company_id', $company->company_id)->get();
        $totalRevenue = $orders->whereIn('order_status', ['delivered', 'confirmed'])->sum('total_amount');
        $totalExpenses = 0; // If you have expenses, fetch and sum here
        $profit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? round(($profit / $totalRevenue) * 100, 1) : 0;

        // Calculate trends (compare this month to last month)
        $startOfThisMonth = now()->startOfMonth();
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        // Revenue trend
        $revenue_this = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->whereIn('order_status', ['delivered', 'confirmed'])
            ->where('created_at', '>=', $startOfThisMonth)
            ->sum('total_amount');
        $revenue_last = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->whereIn('order_status', ['delivered', 'confirmed'])
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_amount');
        $revenue_trend = $revenue_last > 0 ? round((($revenue_this - $revenue_last) / $revenue_last) * 100, 1) : ($revenue_this > 0 ? 100 : 0);

        // Expenses trend (mock data for now)
        $expenses_trend = 0;

        // Profit trend
        $profit_trend = $revenue_trend; // Simplified for now

        // Profit margin trend
        $margin_trend = $revenue_trend; // Simplified for now

        $trends = [
            'totalRevenue' => $revenue_trend,
            'totalExpenses' => $expenses_trend,
            'profit' => $profit_trend,
            'profitMargin' => $margin_trend
        ];

        $transactions = $orders->map(function($order) {
            return [
                'order_id' => $order->order_id,
                'amount' => $order->total_amount,
                'payment_status' => $order->order_status === 'delivered' ? 'paid' : 'pending',
                'created_at' => $order->created_at->format('Y-m-d'),
            ];
        });

        return view('farmers.financials.index', compact('totalRevenue', 'totalExpenses', 'profit', 'profitMargin', 'transactions', 'trends'));
    }

    public function pricing()
    {
        $user = Auth::user();
        $company = $user->company;
        $pricing = Pricing::where('company_id', $company->company_id)->get()->map(function($price) {
            return [
                'coffee_variety' => ucfirst($price->coffee_variety),
                'grade' => ucfirst(str_replace('_', ' ', $price->grade)),
                'unit_price' => $price->unit_price,
                'current_market_price' => $price->unit_price,
                'last_updated' => $price->updated_at->format('Y-m-d'),
                'description' => 'Latest price for ' . ucfirst($price->coffee_variety) . ' ' . ucfirst(str_replace('_', ' ', $price->grade)),
            ];
        })->values();

        if ($pricing->isEmpty()) {
            $defaultVarieties = [
                ['coffee_variety' => 'Arabica', 'grade' => 'Grade 1'],
                ['coffee_variety' => 'Arabica', 'grade' => 'Grade 2'],
                ['coffee_variety' => 'Robusta', 'grade' => 'Grade 1'],
                ['coffee_variety' => 'Robusta', 'grade' => 'Grade 2'],
            ];
            $pricing = collect($defaultVarieties)->map(function($item) {
                return [
                    'coffee_variety' => $item['coffee_variety'],
                    'grade' => $item['grade'],
                    'unit_price' => '',
                    'current_market_price' => '',
                    'last_updated' => now()->format('Y-m-d'),
                    'description' => 'Set price for ' . $item['coffee_variety'] . ' ' . $item['grade'],
                ];
            });
        }

        // Mock market trends (could be calculated from order history)
        $marketTrends = [
            'arabica_trend' => '+2.5%',
            'robusta_trend' => '+1.8%',
            'market_volatility' => 'Low',
            'recommendation' => 'Consider slight price increase for Arabica Premium'
        ];

        return view('farmers.financials.pricing', compact('pricing', 'marketTrends'));
    }

    public function updatePricing(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        $request->validate([
            'prices' => 'required|array|min:1',
            'prices.*.unit_price' => 'required|numeric|min:0',
        ]);
        foreach ($request->prices as $price) {
            Pricing::updateOrCreate(
                [
                    'company_id' => $company->company_id,
                    'coffee_variety' => strtolower($price['coffee_variety']),
                    'grade' => strtolower(str_replace(' ', '_', $price['grade'])),
                ],
                [
                    'unit_price' => $price['unit_price'],
                ]
            );
        }
        return redirect()->route('farmers.financials.pricing')
            ->with('success', 'Pricing updated successfully. Your new prices are now active.');
    }

    public function reports()
    {
        $user = Auth::user();
        $company = $user->company;
        $orders = FarmerOrder::where('farmer_company_id', $company->company_id)->get();
        
        // Generate financial reports data
        $monthlyRevenue = $orders->whereIn('order_status', ['delivered', 'confirmed'])
            ->groupBy(function($order) {
                return $order->created_at->format('Y-m');
            })
            ->map(function($group) {
                return $group->sum('total_amount');
            });

        $reports = [
            'monthly_revenue' => $monthlyRevenue,
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->whereIn('order_status', ['delivered', 'confirmed'])->count(),
            'pending_orders' => $orders->where('order_status', 'pending')->count(),
        ];

        return view('farmers.financials.reports', compact('reports'));
    }

    public function expenses()
    {
        $user = Auth::user();
        $company = $user->company;
        
        // Mock expenses data - in a real app, you'd have an expenses table
        $expenses = [
            'labor' => 0,
            'fertilizers' => 0,
            'equipment' => 0,
            'transportation' => 0,
            'other' => 0
        ];

        return view('farmers.financials.expenses', compact('expenses'));
    }

    public function cashflow()
    {
        $user = Auth::user();
        $company = $user->company;
        $orders = FarmerOrder::where('farmer_company_id', $company->company_id)->get();
        
        // Calculate cash flow data
        $cashflow = [
            'inflows' => $orders->whereIn('order_status', ['delivered', 'confirmed'])->sum('total_amount'),
            'outflows' => 0, // Mock data
            'net_cashflow' => $orders->whereIn('order_status', ['delivered', 'confirmed'])->sum('total_amount'),
        ];

        return view('farmers.financials.cashflow', compact('cashflow'));
    }

    public function forecasting()
    {
        $user = Auth::user();
        $company = $user->company;
        $orders = FarmerOrder::where('farmer_company_id', $company->company_id)->get();
        
        // Generate forecasting data
        $forecasting = [
            'projected_revenue' => $orders->sum('total_amount') * 1.15,
            'growth_rate' => 15,
            'seasonal_factors' => ['peak' => 'March-May', 'low' => 'December-February'],
        ];

        return view('farmers.financials.forecasting', compact('forecasting'));
    }
}