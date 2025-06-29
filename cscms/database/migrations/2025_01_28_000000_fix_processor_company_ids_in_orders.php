<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix farmer_orders table
        $this->fixProcessorCompanyIds('farmer_orders');
        
        // Fix retailer_orders table
        $this->fixProcessorCompanyIds('retailer_orders');
        
        // Fix employees table
        $this->fixProcessorCompanyIds('employees');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed as this is a data fix
    }

    /**
     * Fix processor_company_id fields that might contain user IDs instead of company IDs
     */
    private function fixProcessorCompanyIds(string $tableName): void
    {
        // Get all processor companies
        $processorCompanies = DB::table('companies')
            ->where('company_type', 'processor')
            ->get(['company_id', 'company_name']);

        if ($processorCompanies->isEmpty()) {
            return;
        }

        // Get the first processor company as default
        $defaultProcessorCompany = $processorCompanies->first();

        // Get all users who are processors
        $processorUsers = DB::table('users')
            ->where('user_type', 'processor')
            ->get(['id', 'company_id']);

        // Create a mapping of user_id to company_id
        $userToCompanyMap = [];
        foreach ($processorUsers as $user) {
            $userToCompanyMap[$user->id] = $user->company_id;
        }

        // Fix records where processor_company_id contains user IDs
        $records = DB::table($tableName)->get();
        
        foreach ($records as $record) {
            $processorCompanyId = $record->processor_company_id;
            
            // Check if this ID exists in the companies table
            $companyExists = DB::table('companies')
                ->where('company_id', $processorCompanyId)
                ->where('company_type', 'processor')
                ->exists();

            // If it doesn't exist as a company, it might be a user ID
            if (!$companyExists && isset($userToCompanyMap[$processorCompanyId])) {
                // Update the record to use the correct company_id
                DB::table($tableName)
                    ->where('id', $record->id)
                    ->update(['processor_company_id' => $userToCompanyMap[$processorCompanyId]]);
                
                \Log::info("Fixed {$tableName} record {$record->id}: updated processor_company_id from user ID {$processorCompanyId} to company ID {$userToCompanyMap[$processorCompanyId]}");
            } elseif (!$companyExists) {
                // If it's neither a valid company ID nor a user ID, set it to the default processor company
                DB::table($tableName)
                    ->where('id', $record->id)
                    ->update(['processor_company_id' => $defaultProcessorCompany->company_id]);
                
                \Log::info("Fixed {$tableName} record {$record->id}: set processor_company_id to default processor company {$defaultProcessorCompany->company_id}");
            }
        }
    }
}; 