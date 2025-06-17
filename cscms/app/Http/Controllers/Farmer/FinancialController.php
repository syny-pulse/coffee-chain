<?php

namespace App\Farmers\Controllers;

use App\Farmers\Services\FinancialService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FinancialController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function index()
    {
        try {
            $financialSummary = $this->financialService->getFinancialSummary();
            return view('farmers.financials.index', $financialSummary);
        } catch (\Exception $e) {
            Log::error('Financial index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading financials.');
        }
    }

    public function pricing()
    {
        try {
            $pricing = $this->financialService->getPricing();
            return view('farmers.financials.pricing', compact('pricing'));
        } catch (\Exception $e) {
            Log::error('Pricing error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading pricing.');
        }
    }

    public function updatePricing(Request $request)
    {
        try {
            $this->financialService->updatePricing($request->input('prices', []));
            return redirect()->route('farmers.financials.index')->with('success', 'Pricing updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Pricing update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating pricing.')->withInput();
        }
    }
}