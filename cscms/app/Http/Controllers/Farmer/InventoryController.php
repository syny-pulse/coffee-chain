<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Farmer\FarmerHarvest;

class InventoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        $inventory = FarmerHarvest::where('company_id', $company->company_id)->orderByDesc('harvest_date')->get();

        // Calculate summary statistics
        $totalStock = $inventory->sum('quantity_kg');
        $totalReserved = $inventory->sum(function($item) {
            return $item->quantity_kg - $item->available_quantity_kg;
        });
        $totalAvailable = $inventory->sum('available_quantity_kg');

        // Calculate variety-specific totals
        $arabicaTotal = $inventory->where('coffee_variety', 'arabica')->sum('quantity_kg');
        $robustaTotal = $inventory->where('coffee_variety', 'robusta')->sum('quantity_kg');

        // For demo, assign storage locations based on even/odd id (since not in schema)
        $warehouseATotal = $inventory->filter(function($item) {
            return $item->harvest_id % 2 === 0;
        })->sum('quantity_kg');
        $warehouseBTotal = $inventory->filter(function($item) {
            return $item->harvest_id % 2 !== 0;
        })->sum('quantity_kg');

        // Get unique processing methods count
        $processingMethodsCount = $inventory->pluck('processing_method')->unique()->count();

        // Calculate trends (compare this month to last month)
        $startOfThisMonth = now()->startOfMonth();
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        // Total Stock trend
        $stock_this = FarmerHarvest::where('company_id', $company->company_id)
            ->where('harvest_date', '>=', $startOfThisMonth)
            ->sum('quantity_kg');
        $stock_last = FarmerHarvest::where('company_id', $company->company_id)
            ->whereBetween('harvest_date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('quantity_kg');
        $stock_trend = $stock_last > 0 ? round((($stock_this - $stock_last) / $stock_last) * 100, 1) : ($stock_this > 0 ? 100 : 0);

        // Available Stock trend
        $avail_this = FarmerHarvest::where('company_id', $company->company_id)
            ->where('harvest_date', '>=', $startOfThisMonth)
            ->sum('available_quantity_kg');
        $avail_last = FarmerHarvest::where('company_id', $company->company_id)
            ->whereBetween('harvest_date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('available_quantity_kg');
        $avail_trend = $avail_last > 0 ? round((($avail_this - $avail_last) / $avail_last) * 100, 1) : ($avail_this > 0 ? 100 : 0);

        // Reserved Stock trend
        $reserved_this = FarmerHarvest::where('company_id', $company->company_id)
            ->where('harvest_date', '>=', $startOfThisMonth)
            ->get()->sum(function($item) {
                return $item->quantity_kg - $item->available_quantity_kg;
            });
        $reserved_last = FarmerHarvest::where('company_id', $company->company_id)
            ->whereBetween('harvest_date', [$startOfLastMonth, $endOfLastMonth])
            ->get()->sum(function($item) {
                return $item->quantity_kg - $item->available_quantity_kg;
            });
        $reserved_trend = $reserved_last > 0 ? round((($reserved_this - $reserved_last) / $reserved_last) * 100, 1) : ($reserved_this > 0 ? 100 : 0);

        // Inventory count trend
        $count_this = FarmerHarvest::where('company_id', $company->company_id)
            ->where('harvest_date', '>=', $startOfThisMonth)
            ->count();
        $count_last = FarmerHarvest::where('company_id', $company->company_id)
            ->whereBetween('harvest_date', [$startOfLastMonth, $endOfLastMonth])
            ->count();
        $count_trend = $count_last > 0 ? ($count_this - $count_last) : $count_this;

        $trends = [
            'totalStock' => $stock_trend,
            'totalAvailable' => $avail_trend,
            'totalReserved' => $reserved_trend,
            'inventoryCount' => $count_trend
        ];

        return view('farmers.inventory.index', compact(
            'inventory', 
            'totalStock', 
            'totalReserved', 
            'totalAvailable',
            'arabicaTotal',
            'robustaTotal',
            'warehouseATotal',
            'warehouseBTotal',
            'processingMethodsCount',
            'trends'
        ));
    }

    public function show($id)
    {
        $user = Auth::user();
        $company = $user->company;
        $item = FarmerHarvest::where('company_id', $company->company_id)->findOrFail($id);
        return view('farmers.inventory.show', compact('item'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $company = $user->company;
        $item = FarmerHarvest::where('company_id', $company->company_id)->findOrFail($id);
        return view('farmers.inventory.edit', compact('item'));
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $company = $user->company;
        $item = FarmerHarvest::where('company_id', $company->company_id)->findOrFail($id);
        $item->delete();
        return redirect()->route('farmers.inventory.index')->with('success', 'Inventory item deleted successfully.');
    }
} 