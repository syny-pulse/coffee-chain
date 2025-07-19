<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RetailerOrder;
use App\Models\FarmerOrder;

class WorkDistributionController extends Controller
{
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;

        // Fetch farmer orders with employee names
        $farmerOrders = FarmerOrder::where('farmer_orders.processor_company_id', $companyId)
            ->whereNotNull('farmer_orders.employee_id')
            ->join('employees', 'farmer_orders.employee_id', '=', 'employees.employee_id')
            ->join('companies', 'farmer_orders.farmer_company_id', '=', 'companies.company_id')
            ->select('farmer_orders.order_id', 'employees.employee_name', 'companies.company_name')
            ->orderBy('farmer_orders.created_at', 'desc')
            ->get();

        // Fetch retailer orders with employee names (use processor_retailer_orders table directly)
        $retailerOrders = \Illuminate\Support\Facades\DB::table('processor_retailer_orders')
            ->join('employees', 'processor_retailer_orders.employee_id', '=', 'employees.employee_id')
            ->join('companies', 'processor_retailer_orders.retailer_company_id', '=', 'companies.company_id')
            ->where('processor_retailer_orders.processor_company_id', $companyId)
            ->whereNotNull('processor_retailer_orders.employee_id')
            ->select('processor_retailer_orders.order_id', 'employees.employee_name', 'companies.company_name')
            ->orderBy('processor_retailer_orders.created_at', 'desc')
            ->get();
        return view('processor.workDistribution', compact('farmerOrders', 'retailerOrders'));
    }
}
