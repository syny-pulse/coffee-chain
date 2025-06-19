<?php

namespace App\Farmers\Controllers;

use App\Farmers\Repositories\FarmerOrderRepository;
use App\Farmers\Services\FarmerHarvestService;
use App\Farmers\Services\FinancialService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HarvestController extends Controller
{
    protected $harvestService;
    protected $orderRepository;
    protected $financialService;

    public function __construct(FarmerHarvestService $harvestService, FarmerOrderRepository $orderRepository, FinancialService $financialService)
    {
        $this->harvestService = $harvestService;
        $this->orderRepository = $orderRepository;
        $this->financialService = $financialService;
    }

    public function dashboard()
    {
        try {
            $totalHarvest = $this->harvestService->getAll()->sum('quantity_kg');
            $availableStock = $this->harvestService->getAll()->sum('available_quantity_kg');
            $orders = $this->orderRepository->getRecent();
            $financialSummary = $this->financialService->getFinancialSummary();
            $totalRevenue = $financialSummary['totalRevenue'];
            $profitMargin = $financialSummary['profitMargin'];

            return view('farmers.dashboard', compact('totalHarvest', 'availableStock', 'orders', 'totalRevenue', 'profitMargin'));
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the dashboard.');
        }
    }

    public function index()
    {
        try {
            $harvests = $this->harvestService->getAll();
            return view('farmers.harvests.index', compact('harvests'));
        } catch (\Exception $e) {
            Log::error('Harvest index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading harvests.');
        }
    }

    public function create()
    {
        return view('farmers.harvests.create');
    }

    public function store(Request $request)
    {
        try {
            $this->harvestService->create($request->all());
            return redirect()->route('farmers.harvests.index')->with('success', 'Harvest recorded successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Harvest store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while saving the harvest.')->withInput();
        }
    }

    public function edit($harvest_id)
    {
        try {
            $harvest = $this->harvestService->getAll()->find($harvest_id);
            return view('farmers.harvests.edit', compact('harvest'));
        } catch (\Exception $e) {
            Log::error('Harvest edit error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the harvest.');
        }
    }

    public function update(Request $request, $harvest_id)
    {
        try {
            $this->harvestService->update($harvest_id, $request->all());
            return redirect()->route('farmers.harvests.index')->with('success', 'Harvest updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Harvest update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the harvest.')->withInput();
        }
    }

    public function destroy($harvest_id)
    {
        try {
            $this->harvestService->delete($harvest_id);
            return redirect()->route('farmers.harvests.index')->with('success', 'Harvest deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Harvest delete error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the harvest.');
        }
    }

    public function inventory()
    {
        try {
            $inventory = $this->harvestService->getInventory();
            return view('farmers.inventory.index', compact('inventory'));
        } catch (\Exception $e) {
            Log::error('Inventory error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading inventory.');
        }
    }
}