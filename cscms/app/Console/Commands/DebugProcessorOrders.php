<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\FarmerOrder;
use App\Models\RetailerOrder;
use App\Models\Employee;

class DebugProcessorOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:processor-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug processor orders to identify data inconsistencies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Debugging Processor Orders ===');

        // Get all processor users
        $processorUsers = User::where('user_type', 'processor')->get();
        $this->info("\nProcessor Users:");
        foreach ($processorUsers as $user) {
            $this->line("- User ID: {$user->id}, Company ID: {$user->company_id}, Name: {$user->name}");
        }

        // Get all processor companies
        $processorCompanies = DB::table('companies')
            ->where('company_type', 'processor')
            ->get();
        $this->info("\nProcessor Companies:");
        foreach ($processorCompanies as $company) {
            $this->line("- Company ID: {$company->company_id}, Name: {$company->company_name}");
        }

        // Check farmer orders
        $this->info("\nFarmer Orders:");
        $farmerOrders = FarmerOrder::all();
        foreach ($farmerOrders as $order) {
            $companyExists = DB::table('companies')
                ->where('company_id', $order->processor_company_id)
                ->where('company_type', 'processor')
                ->exists();
            
            $status = $companyExists ? '✓ Valid' : '✗ Invalid';
            $this->line("- Order ID: {$order->order_id}, Processor Company ID: {$order->processor_company_id} {$status}");
        }

        // Check retailer orders
        $this->info("\nRetailer Orders:");
        $retailerOrders = RetailerOrder::all();
        foreach ($retailerOrders as $order) {
            $companyExists = DB::table('companies')
                ->where('company_id', $order->processor_company_id)
                ->where('company_type', 'processor')
                ->exists();
            
            $status = $companyExists ? '✓ Valid' : '✗ Invalid';
            $this->line("- Order ID: {$order->order_id}, Processor Company ID: {$order->processor_company_id} {$status}");
        }

        // Check employees
        $this->info("\nEmployees:");
        $employees = Employee::all();
        foreach ($employees as $employee) {
            $companyExists = DB::table('companies')
                ->where('company_id', $employee->processor_company_id)
                ->where('company_type', 'processor')
                ->exists();
            
            $status = $companyExists ? '✓ Valid' : '✗ Invalid';
            $this->line("- Employee ID: {$employee->employee_id}, Processor Company ID: {$employee->processor_company_id} {$status}");
        }

        $this->info("\n=== Debug Complete ===");
    }
} 