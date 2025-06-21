<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Farmer\FarmerHarvest;
use App\Models\FarmerOrder;

class AnalyticsController extends Controller
{
    public function reports()
    {
        $user = Auth::user();
        $company = $user->company;
        $harvests = FarmerHarvest::where('company_id', $company->company_id)->get();
        $orders = FarmerOrder::where('farmer_company_id', $company->company_id)->get();

        // Harvest reports by variety and quarter
        $harvestReports = $harvests->groupBy(function($h) {
            return $h->coffee_variety . '-' . $h->harvest_date->format('Y') . '-Q' . ceil($h->harvest_date->format('n')/3);
        })->map(function($group) {
            $first = $group->first();
            return [
                'coffee_variety' => ucfirst($first->coffee_variety),
                'total_quantity_kg' => $group->sum('quantity_kg'),
                'period' => 'Q' . ceil($first->harvest_date->format('n')/3) . ' ' . $first->harvest_date->format('Y'),
                'growth_rate' => rand(5, 20), // Simulated
                'quality_score' => rand(70, 90) / 10 // Simulated
            ];
        })->values();

        $projectedSales = $orders->sum('total_amount') * 1.15;
        $currentRevenue = $orders->whereIn('order_status', ['delivered', 'confirmed'])->sum('total_amount');
        $revenueGrowth = $orders->count() > 0 ? rand(5, 20) : 0;
        $profitMargin = $currentRevenue > 0 ? rand(20, 40) : 0;
        $averageOrderValue = $orders->avg('total_amount') ?? 0;

        $performanceMetrics = [
            'total_harvests' => $harvests->count(),
            'total_orders' => $orders->count(),
            'customer_satisfaction' => rand(40, 50) / 10, // Simulated
            'on_time_delivery' => rand(85, 99), // Simulated
            'quality_rating' => rand(70, 90) / 10 // Simulated
        ];

        // Trends for charts (last 6 months)
        $months = collect(range(0, 5))->map(function($i) {
            return now()->subMonths($i)->format('M');
        })->reverse()->values();
        $harvestTrends = [
            'labels' => $months,
            'arabica' => $months->map(fn($m, $i) => $harvests->where('coffee_variety', 'arabica')->where('harvest_date', '>=', now()->subMonths(5-$i)->startOfMonth())->where('harvest_date', '<=', now()->subMonths(5-$i)->endOfMonth())->sum('quantity_kg'))->toArray(),
            'robusta' => $months->map(fn($m, $i) => $harvests->where('coffee_variety', 'robusta')->where('harvest_date', '>=', now()->subMonths(5-$i)->startOfMonth())->where('harvest_date', '<=', now()->subMonths(5-$i)->endOfMonth())->sum('quantity_kg'))->toArray(),
        ];
        $revenueTrends = [
            'labels' => $months,
            'data' => $months->map(fn($m, $i) => $orders->where('created_at', '>=', now()->subMonths(5-$i)->startOfMonth())->where('created_at', '<=', now()->subMonths(5-$i)->endOfMonth())->sum('total_amount'))->toArray(),
        ];

        // Top buyers (simulate by notes field)
        $topBuyers = $orders->groupBy('notes')->map(function($group, $buyer) {
            return [
                'name' => $buyer ?: 'Unknown Buyer',
                'orders' => $group->count(),
                'total_value' => $group->sum('total_amount'),
                'growth' => rand(5, 20),
            ];
        })->sortByDesc('total_value')->take(4)->values();

        $coffeeVarieties = [
            'Arabica' => $harvests->where('coffee_variety', 'arabica')->sum('quantity_kg'),
            'Robusta' => $harvests->where('coffee_variety', 'robusta')->sum('quantity_kg'),
        ];

        $seasonalAnalysis = [
            'peak_season' => 'March - May',
            'low_season' => 'December - February',
            'optimal_harvest_time' => 'April',
            'weather_impact' => 'Minimal'
        ];

        return view('farmers.analytics.reports', compact(
            'harvestReports',
            'projectedSales',
            'currentRevenue',
            'revenueGrowth',
            'profitMargin',
            'averageOrderValue',
            'performanceMetrics',
            'harvestTrends',
            'revenueTrends',
            'topBuyers',
            'coffeeVarieties',
            'seasonalAnalysis'
        ));
    }
}
