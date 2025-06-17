<?php

namespace App\Farmers\Controllers;

use App\Farmers\Repositories\FarmerHarvestRepository;
use App\Farmers\Repositories\FarmerOrderRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    protected $harvestRepository;
    protected $orderRepository;

    public function __construct(FarmerHarvestRepository $harvestRepository, FarmerOrderRepository $orderRepository)
    {
        $this->harvestRepository = $harvestRepository;
        $this->orderRepository = $orderRepository;
    }

    public function reports()
    {
        try {
            $harvestReports = $this->harvestRepository->getAll()
                ->groupBy('coffee_variety')
                ->map(function ($group, $variety) {
                    return [
                        'coffee_variety' => $variety,
                        'total_quantity_kg' => $group->sum('quantity_kg'),
                        'period' => $group->pluck('harvest_date')->map->format('Y')->unique()->join(','),
                    ];
                })->values();
            $projectedSales = $this->orderRepository->getProjectedSales();

            return view('farmers.analytics.reports', compact('harvestReports', 'projectedSales'));
        } catch (\Exception $e) {
            Log::error('Reports error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading reports.');
        }
    }
}