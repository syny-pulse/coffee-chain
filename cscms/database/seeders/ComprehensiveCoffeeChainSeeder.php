<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductRecipe;
use App\Models\FarmerOrder;
use App\Models\RetailerOrder;
use App\Models\RetailerOrderItem;
use App\Models\Employee;
use App\Models\Message;
use App\Models\RetailerInventory;
use App\Models\ProcessorRawMaterialInventory;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Pricing;
use App\Models\Farmer\FarmerHarvest;

class ComprehensiveCoffeeChainSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data - only if tables exist
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $tablesToTruncate = [
            'payments',
            'invoices', 
            'retailer_order_items',
            'processor_retailer_orders',
            'retailer_order',
            'farmer_orders',
            'retailer_inventory',
            'processor_raw_material_inventory',
            'farmer_harvest',
            'product_recipes',
            'products',
            'employees',
            'messages',
            'pricings',
            'users',
            'companies'
        ];
        
        foreach ($tablesToTruncate as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create Companies (15+ companies)
        if (Schema::hasTable('companies')) {
            $companies = $this->createCompanies();
        }
        
        // 2. Create Users (20+ users)
        if (Schema::hasTable('users')) {
            $users = $this->createUsers($companies ?? collect());
        }
        
        // 3. Create Product Recipes (15+ recipes)
        if (Schema::hasTable('product_recipes')) {
            $recipes = $this->createProductRecipes();
        }
        
        // 4. Create Products (25+ products)
        if (Schema::hasTable('products')) {
            $products = $this->createProducts($users ?? collect());
        }
        
        // 5. Create Employees (15+ employees)
        if (Schema::hasTable('employees')) {
            $employees = $this->createEmployees($companies ?? collect());
        }
        
        // 6. Create Farmer Harvests (20+ harvests)
        if (Schema::hasTable('farmer_harvest')) {
            $harvests = $this->createFarmerHarvests($companies ?? collect());
        }
        
        // 7. Create Processor Raw Material Inventory (15+ inventory items)
        if (Schema::hasTable('processor_raw_material_inventory')) {
            $rawInventory = $this->createProcessorRawMaterialInventory($companies ?? collect());
        }
        
        // 8. Create Retailer Inventory (20+ inventory items)
        if (Schema::hasTable('retailer_inventory')) {
            $retailerInventory = $this->createRetailerInventory();
        }
        
        // 9. Create Pricing (20+ pricing records)
        if (Schema::hasTable('pricings')) {
            $pricing = $this->createPricing($companies ?? collect());
        }
        
        // 10. Create Farmer Orders (25+ orders)
        if (Schema::hasTable('farmer_orders')) {
            $farmerOrders = $this->createFarmerOrders($companies ?? collect(), $employees ?? collect());
        }
        
        // 11. Create Retailer Orders (30+ orders)
        if (Schema::hasTable('processor_retailer_orders')) {
            $retailerOrders = $this->createRetailerOrders($companies ?? collect(), $employees ?? collect());
        }
        
        // 12. Create Retailer Order Items (50+ order items)
        if (Schema::hasTable('retailer_order_items')) {
            $orderItems = $this->createRetailerOrderItems($retailerOrders ?? collect(), $products ?? collect());
        }
        
        // 13. Create Messages (40+ messages)
        if (Schema::hasTable('messages')) {
            $messages = $this->createMessages($users ?? collect(), $companies ?? collect());
        }
        
        // 14. Create Invoices (25+ invoices)
        if (Schema::hasTable('invoices')) {
            $invoices = $this->createInvoices($farmerOrders ?? collect(), $retailerOrders ?? collect());
        }
        
        // 15. Create Payments (30+ payments)
        if (Schema::hasTable('payments')) {
            $payments = $this->createPayments($invoices ?? collect());
        }
    }

    private function createCompanies()
    {
        $companies = [
            // Farmers
            ['company_name' => 'Green Valley Coffee Farm', 'email' => 'info@greenvalley.com', 'company_type' => 'farmer', 'phone' => '+254700123456', 'address' => 'Nyeri, Central Kenya', 'registration_number' => 'FARM001', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Mountain View Estate', 'email' => 'contact@mountainview.co.ke', 'company_type' => 'farmer', 'phone' => '+254700123457', 'address' => 'Kiambu, Central Kenya', 'registration_number' => 'FARM002', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Highland Coffee Growers', 'email' => 'sales@highlandcoffee.com', 'company_type' => 'farmer', 'phone' => '+254700123458', 'address' => 'Murang\'a, Central Kenya', 'registration_number' => 'FARM003', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Arabica Valley Farm', 'email' => 'info@arabicavalley.com', 'company_type' => 'farmer', 'phone' => '+254700123459', 'address' => 'Thika, Central Kenya', 'registration_number' => 'FARM004', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Robusta Hills Estate', 'email' => 'contact@robustahills.com', 'company_type' => 'farmer', 'phone' => '+254700123460', 'address' => 'Machakos, Eastern Kenya', 'registration_number' => 'FARM005', 'acceptance_status' => 'accepted'],
            
            // Processors
            ['company_name' => 'Kenya Coffee Processors Ltd', 'email' => 'info@kenyacoffeeprocessors.com', 'company_type' => 'processor', 'phone' => '+254700123461', 'address' => 'Nairobi Industrial Area', 'registration_number' => 'PROC001', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Premium Roasters Kenya', 'email' => 'contact@premiumroasters.co.ke', 'company_type' => 'processor', 'phone' => '+254700123462', 'address' => 'Thika Industrial Park', 'registration_number' => 'PROC002', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Artisan Coffee Works', 'email' => 'sales@artisancoffee.com', 'company_type' => 'processor', 'phone' => '+254700123463', 'address' => 'Nakuru, Rift Valley', 'registration_number' => 'PROC003', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Mombasa Coffee Millers', 'email' => 'info@mombasacoffee.com', 'company_type' => 'processor', 'phone' => '+254700123464', 'address' => 'Mombasa Port Area', 'registration_number' => 'PROC004', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Central Kenya Processors', 'email' => 'contact@centralkenya.com', 'company_type' => 'processor', 'phone' => '+254700123465', 'address' => 'Nyeri Industrial Area', 'registration_number' => 'PROC005', 'acceptance_status' => 'accepted'],
            
            // Retailers
            ['company_name' => 'Java Junction Coffee Shop', 'email' => 'info@javajunction.com', 'company_type' => 'retailer', 'phone' => '+254700123466', 'address' => 'Westlands, Nairobi', 'registration_number' => 'RET001', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Brew & Bean Café', 'email' => 'contact@brewandbean.co.ke', 'company_type' => 'retailer', 'phone' => '+254700123467', 'address' => 'Kilimani, Nairobi', 'registration_number' => 'RET002', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Coffee Culture Kenya', 'email' => 'sales@coffeeculture.com', 'company_type' => 'retailer', 'phone' => '+254700123468', 'address' => 'Lavington, Nairobi', 'registration_number' => 'RET003', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Mocha Moments', 'email' => 'info@mochamoments.com', 'company_type' => 'retailer', 'phone' => '+254700123469', 'address' => 'Karen, Nairobi', 'registration_number' => 'RET004', 'acceptance_status' => 'accepted'],
            ['company_name' => 'Espresso Express', 'email' => 'contact@espressoexpress.co.ke', 'company_type' => 'retailer', 'phone' => '+254700123470', 'address' => 'CBD, Nairobi', 'registration_number' => 'RET005', 'acceptance_status' => 'accepted'],
        ];

        $companiesToInsert = [];
        foreach ($companies as $companyData) {
            $companiesToInsert[] = [
                'company_name' => $companyData['company_name'],
                'email' => $companyData['email'],
                'company_type' => $companyData['company_type'],
                'phone' => $companyData['phone'],
                'address' => $companyData['address'],
                'registration_number' => $companyData['registration_number'],
                'acceptance_status' => $companyData['acceptance_status'],
                'pdf_path' => 'documents/' . strtolower(str_replace(' ', '_', $companyData['company_name'])) . '.pdf',
                'financial_risk_rating' => rand(10, 90) / 10,
                'reputational_risk_rating' => rand(10, 90) / 10,
                'compliance_risk_rating' => rand(10, 90) / 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return Company::insert($companiesToInsert);
    }

    private function createUsers($companies)
    {
        $users = [];
        $companyIds = Company::pluck('company_id', 'company_type')->toArray();
        
        // Admin users
        $users[] = [
            'name' => 'System Administrator',
            'email' => 'admin@coffeechain.com',
            'password' => Hash::make('password123'),
            'user_type' => 'admin',
            'company_id' => null,
            'phone' => '+254700000001',
            'address' => 'Nairobi, Kenya',
            'status' => 'active',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Farmer users
        $farmerCompanies = Company::where('company_type', 'farmer')->get();
        foreach ($farmerCompanies as $company) {
            $users[] = [
                'name' => $company->company_name . ' Manager',
                'email' => 'manager@' . strtolower(str_replace(' ', '', $company->company_name)) . '.com',
                'password' => Hash::make('password123'),
                'user_type' => 'farmer',
                'company_id' => $company->company_id,
                'phone' => $company->phone,
                'address' => $company->address,
                'status' => 'active',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Processor users
        $processorCompanies = Company::where('company_type', 'processor')->get();
        foreach ($processorCompanies as $company) {
            $users[] = [
                'name' => $company->company_name . ' Manager',
                'email' => 'manager@' . strtolower(str_replace(' ', '', $company->company_name)) . '.com',
                'password' => Hash::make('password123'),
                'user_type' => 'processor',
                'company_id' => $company->company_id,
                'phone' => $company->phone,
                'address' => $company->address,
                'status' => 'active',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Retailer users
        $retailerCompanies = Company::where('company_type', 'retailer')->get();
        foreach ($retailerCompanies as $company) {
            $users[] = [
                'name' => $company->company_name . ' Manager',
                'email' => 'manager@' . strtolower(str_replace(' ', '', $company->company_name)) . '.com',
                'password' => Hash::make('password123'),
                'user_type' => 'retailer',
                'company_id' => $company->company_id,
                'phone' => $company->phone,
                'address' => $company->address,
                'status' => 'active',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return User::insert($users);
    }

    private function createProductRecipes()
    {
        $recipes = [
            // Drinking Coffee Recipes
            ['product_name' => 'drinking_coffee', 'recipe_name' => 'Classic Espresso', 'coffee_variety' => 'arabica', 'processing_method' => 'washed', 'required_grade' => 'grade_1', 'percentage_composition' => 100.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_name' => 'drinking_coffee', 'recipe_name' => 'Smooth Latte', 'coffee_variety' => 'arabica', 'processing_method' => 'washed', 'required_grade' => 'grade_2', 'percentage_composition' => 80.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_name' => 'drinking_coffee', 'recipe_name' => 'Rich Cappuccino', 'coffee_variety' => 'arabica', 'processing_method' => 'natural', 'required_grade' => 'grade_2', 'percentage_composition' => 85.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_name' => 'drinking_coffee', 'recipe_name' => 'Bold Americano', 'coffee_variety' => 'robusta', 'processing_method' => 'washed', 'required_grade' => 'grade_3', 'percentage_composition' => 100.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Roasted Coffee Recipes
            ['product_name' => 'roasted_coffee', 'recipe_name' => 'Light Roast Arabica', 'coffee_variety' => 'arabica', 'processing_method' => 'washed', 'required_grade' => 'grade_1', 'percentage_composition' => 100.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_name' => 'roasted_coffee', 'recipe_name' => 'Medium Roast Blend', 'coffee_variety' => 'arabica', 'processing_method' => 'natural', 'required_grade' => 'grade_2', 'percentage_composition' => 70.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_name' => 'roasted_coffee', 'recipe_name' => 'Dark Roast Robusta', 'coffee_variety' => 'robusta', 'processing_method' => 'washed', 'required_grade' => 'grade_3', 'percentage_composition' => 100.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_name' => 'roasted_coffee', 'recipe_name' => 'Premium Single Origin', 'coffee_variety' => 'arabica', 'processing_method' => 'honey', 'required_grade' => 'grade_1', 'percentage_composition' => 100.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Coffee Scents Recipes
            ['product_name' => 'coffee_scents', 'recipe_name' => 'Morning Brew Scent', 'coffee_variety' => 'arabica', 'processing_method' => 'natural', 'required_grade' => 'grade_4', 'percentage_composition' => 60.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_name' => 'coffee_scents', 'recipe_name' => 'Roasted Bean Aroma', 'coffee_variety' => 'robusta', 'processing_method' => 'washed', 'required_grade' => 'grade_4', 'percentage_composition' => 50.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_name' => 'coffee_scents', 'recipe_name' => 'Vanilla Coffee Blend', 'coffee_variety' => 'arabica', 'processing_method' => 'honey', 'required_grade' => 'grade_3', 'percentage_composition' => 40.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Coffee Soap Recipes
            ['product_name' => 'coffee_soap', 'recipe_name' => 'Exfoliating Coffee Soap', 'coffee_variety' => 'robusta', 'processing_method' => 'natural', 'required_grade' => 'grade_5', 'percentage_composition' => 30.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_name' => 'coffee_soap', 'recipe_name' => 'Luxury Coffee Soap', 'coffee_variety' => 'arabica', 'processing_method' => 'washed', 'required_grade' => 'grade_4', 'percentage_composition' => 25.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_name' => 'coffee_soap', 'recipe_name' => 'Organic Coffee Soap', 'coffee_variety' => 'arabica', 'processing_method' => 'honey', 'required_grade' => 'grade_4', 'percentage_composition' => 35.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        return ProductRecipe::insert($recipes);
    }

    private function createProducts($users)
    {
        $products = [];
        $farmerUsers = User::where('user_type', 'farmer')->get();
        
        $productTypes = ['arabica', 'robusta'];
        $processingMethods = ['natural', 'washed', 'honey'];
        $roastLevels = ['light', 'medium', 'dark', 'espresso', 'french'];
        $grades = ['grade_1', 'grade_2', 'grade_3', 'grade_4', 'grade_5'];
        $statuses = ['available', 'reserved', 'sold'];
        
        for ($i = 0; $i < 25; $i++) {
            $user = $farmerUsers->random();
            $productType = $productTypes[array_rand($productTypes)];
            $processingMethod = $processingMethods[array_rand($processingMethods)];
            $roastLevel = $roastLevels[array_rand($roastLevels)];
            $grade = $grades[array_rand($grades)];
            $status = $statuses[array_rand($statuses)];
            
            $products[] = [
                'user_id' => $user->id,
                'name' => ucfirst($productType) . ' ' . ucfirst($processingMethod) . ' ' . ucfirst($grade),
                'product_type' => $productType,
                'origin_country' => 'Kenya',
                'processing_method' => $processingMethod,
                'roast_level' => $roastLevel,
                'quantity_kg' => rand(50, 500),
                'price_per_kg' => rand(200, 800),
                'quality_score' => rand(70, 95) / 10,
                'harvest_date' => Carbon::now()->subDays(rand(30, 180)),
                'processing_date' => Carbon::now()->subDays(rand(15, 60)),
                'expiry_date' => Carbon::now()->addDays(rand(180, 365)),
                'description' => 'Premium ' . $productType . ' coffee processed using ' . $processingMethod . ' method, ' . $grade . ' quality.',
                'status' => $status,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return Product::insert($products);
    }

    private function createEmployees($companies)
    {
        $employees = [];
        $processorCompanies = Company::where('company_type', 'processor')->get();
        
        $skillSets = ['grading', 'roasting', 'packaging', 'logistics', 'quality_control', 'maintenance'];
        $stations = ['grading', 'roasting', 'packaging', 'logistics', 'quality_control', 'maintenance'];
        $availabilityStatuses = ['available', 'busy', 'on_break', 'off_duty', 'on_leave'];
        $shiftSchedules = ['morning', 'afternoon', 'night', 'flexible'];
        $statuses = ['active', 'inactive', 'terminated'];
        
        foreach ($processorCompanies as $company) {
            for ($i = 0; $i < 3; $i++) {
                $skillSet = $skillSets[array_rand($skillSets)];
                $primaryStation = $stations[array_rand($stations)];
                $currentStation = $stations[array_rand($stations)];
                
                $employees[] = [
                    'processor_company_id' => $company->company_id,
                    'employee_name' => 'Employee ' . ($i + 1) . ' - ' . $company->company_name,
                    'employee_code' => 'EMP' . $company->company_id . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'skill_set' => $skillSet,
                    'primary_station' => $primaryStation,
                    'current_station' => $currentStation,
                    'availability_status' => $availabilityStatuses[array_rand($availabilityStatuses)],
                    'shift_schedule' => $shiftSchedules[array_rand($shiftSchedules)],
                    'hourly_rate' => rand(150, 500),
                    'hire_date' => Carbon::now()->subDays(rand(30, 365)),
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        return Employee::insert($employees);
    }

    private function createFarmerHarvests($companies)
    {
        $harvests = [];
        $farmerCompanies = Company::where('company_type', 'farmer')->get();
        
        $coffeeVarieties = ['arabica', 'robusta'];
        $processingMethods = ['natural', 'washed', 'honey'];
        $grades = ['grade_1', 'grade_2', 'grade_3', 'grade_4', 'grade_5'];
        $availabilityStatuses = ['available', 'reserved', 'sold_out', 'expired'];
        
        foreach ($farmerCompanies as $company) {
            for ($i = 0; $i < 4; $i++) {
                $coffeeVariety = $coffeeVarieties[array_rand($coffeeVarieties)];
                $processingMethod = $processingMethods[array_rand($processingMethods)];
                $grade = $grades[array_rand($grades)];
                $availabilityStatus = $availabilityStatuses[array_rand($availabilityStatuses)];
                
                $quantity = rand(100, 1000);
                $availableQuantity = $availabilityStatus === 'available' ? $quantity : ($availabilityStatus === 'reserved' ? rand(0, $quantity * 0.7) : 0);
                
                $harvests[] = [
                    'company_id' => $company->company_id,
                    'coffee_variety' => $coffeeVariety,
                    'processing_method' => $processingMethod,
                    'grade' => $grade,
                    'quantity_kg' => $quantity,
                    'available_quantity_kg' => $availableQuantity,
                    'harvest_date' => Carbon::now()->subDays(rand(30, 180)),
                    'availability_status' => $availabilityStatus,
                    'quality_notes' => 'High quality ' . $coffeeVariety . ' coffee with ' . $processingMethod . ' processing method.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        return FarmerHarvest::insert($harvests);
    }

    private function createProcessorRawMaterialInventory($companies)
    {
        $inventory = [];
        $processorCompanies = Company::where('company_type', 'processor')->get();
        
        $coffeeVarieties = ['arabica', 'robusta'];
        $processingMethods = ['natural', 'washed', 'honey'];
        $grades = ['grade_1', 'grade_2', 'grade_3', 'grade_4', 'grade_5'];
        
        foreach ($processorCompanies as $company) {
            foreach ($coffeeVarieties as $variety) {
                foreach ($processingMethods as $method) {
                    foreach ($grades as $grade) {
                        $currentStock = rand(500, 2000);
                        $reservedStock = rand(0, $currentStock * 0.3);
                        $availableStock = $currentStock - $reservedStock;
                        
                        $inventory[] = [
                            'processor_company_id' => $company->company_id,
                            'coffee_variety' => $variety,
                            'processing_method' => $method,
                            'grade' => $grade,
                            'current_stock_kg' => $currentStock,
                            'reserved_stock_kg' => $reservedStock,
                            'available_stock_kg' => $availableStock,
                            'average_cost_per_kg' => rand(200, 600),
                            'last_updated' => Carbon::now()->subDays(rand(1, 30)),
                        ];
                    }
                }
            }
        }

        return ProcessorRawMaterialInventory::insert($inventory);
    }

    private function createRetailerInventory()
    {
        $inventory = [];
        
        $productTypes = ['drinking_coffee', 'roasted_coffee', 'coffee_scents', 'coffee_soap'];
        $coffeeBreeds = ['arabica', 'robusta'];
        $roastGrades = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5'];
        
        for ($i = 0; $i < 20; $i++) {
            $inventory[] = [
                'product_type' => $productTypes[array_rand($productTypes)],
                'coffee_breed' => $coffeeBreeds[array_rand($coffeeBreeds)],
                'roast_grade' => $roastGrades[array_rand($roastGrades)],
                'quantity' => rand(50, 500),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return RetailerInventory::insert($inventory);
    }

    private function createPricing($companies)
    {
        $pricing = [];
        $allCompanies = Company::all();
        
        $coffeeVarieties = ['arabica', 'robusta'];
        $grades = ['grade_1', 'grade_2', 'grade_3', 'grade_4', 'grade_5'];
        
        foreach ($allCompanies as $company) {
            foreach ($coffeeVarieties as $variety) {
                foreach ($grades as $grade) {
                    $pricing[] = [
                        'company_id' => $company->company_id,
                        'coffee_variety' => $variety,
                        'grade' => $grade,
                        'unit_price' => rand(150, 800),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
            }
        }

        return Pricing::insert($pricing);
    }

    private function createFarmerOrders($companies, $employees)
    {
        $orders = [];
        $processorCompanies = Company::where('company_type', 'processor')->get();
        $farmerCompanies = Company::where('company_type', 'farmer')->get();
        $employeeIds = Employee::pluck('employee_id')->toArray();
        
        $coffeeVarieties = ['arabica', 'robusta'];
        $processingMethods = ['natural', 'washed', 'honey'];
        $grades = ['grade_1', 'grade_2', 'grade_3', 'grade_4', 'grade_5'];
        $orderStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        for ($i = 0; $i < 25; $i++) {
            $processor = $processorCompanies->random();
            $farmer = $farmerCompanies->random();
            $employeeId = $employeeIds[array_rand($employeeIds)];
            
            $coffeeVariety = $coffeeVarieties[array_rand($coffeeVarieties)];
            $processingMethod = $processingMethods[array_rand($processingMethods)];
            $grade = $grades[array_rand($grades)];
            $orderStatus = $orderStatuses[array_rand($orderStatuses)];
            
            $quantity = rand(100, 1000);
            $unitPrice = rand(200, 600);
            $totalAmount = $quantity * $unitPrice;
            
            $expectedDelivery = Carbon::now()->addDays(rand(7, 30));
            $actualDelivery = $orderStatus === 'delivered' ? $expectedDelivery->copy()->addDays(rand(-3, 3)) : null;
            
            $orders[] = [
                'processor_company_id' => $processor->company_id,
                'farmer_company_id' => $farmer->company_id,
                'employee_id' => $employeeId,
                'coffee_variety' => $coffeeVariety,
                'processing_method' => $processingMethod,
                'grade' => $grade,
                'quantity_kg' => $quantity,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'expected_delivery_date' => $expectedDelivery,
                'actual_delivery_date' => $actualDelivery,
                'order_status' => $orderStatus,
                'notes' => 'Order for ' . $coffeeVariety . ' coffee with ' . $processingMethod . ' processing method, ' . $grade . ' quality.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return FarmerOrder::insert($orders);
    }

    private function createRetailerOrders($companies, $employees)
    {
        $orders = [];
        $processorCompanies = Company::where('company_type', 'processor')->get();
        $retailerCompanies = Company::where('company_type', 'retailer')->get();
        $employeeIds = Employee::pluck('employee_id')->toArray();
        
        $orderStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $shippingAddresses = [
            'Westlands, Nairobi, Kenya',
            'Kilimani, Nairobi, Kenya',
            'Lavington, Nairobi, Kenya',
            'Karen, Nairobi, Kenya',
            'CBD, Nairobi, Kenya'
        ];
        
        for ($i = 0; $i < 30; $i++) {
            $processor = $processorCompanies->random();
            $retailer = $retailerCompanies->random();
            $employeeId = $employeeIds[array_rand($employeeIds)];
            
            $orderStatus = $orderStatuses[array_rand($orderStatuses)];
            $totalAmount = rand(5000, 50000);
            
            $expectedDelivery = Carbon::now()->addDays(rand(7, 30));
            $actualDelivery = $orderStatus === 'delivered' ? $expectedDelivery->copy()->addDays(rand(-3, 3)) : null;
            
            $orders[] = [
                'order_number' => 'RO' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'processor_company_id' => $processor->company_id,
                'retailer_company_id' => $retailer->company_id,
                'employee_id' => $employeeId,
                'total_amount' => $totalAmount,
                'expected_delivery_date' => $expectedDelivery,
                'actual_delivery_date' => $actualDelivery,
                'order_status' => $orderStatus,
                'shipping_address' => $shippingAddresses[array_rand($shippingAddresses)],
                'notes' => 'Retailer order for various coffee products.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return RetailerOrder::insert($orders);
    }

    private function createRetailerOrderItems($retailerOrders, $products)
    {
        $orderItems = [];
        $retailerOrderIds = RetailerOrder::pluck('order_id')->toArray();
        $recipeIds = ProductRecipe::pluck('recipe_id')->toArray();
        
        $productNames = ['drinking_coffee', 'roasted_coffee', 'coffee_scents', 'coffee_soap'];
        $productVariants = ['250g', '500g', '1kg', '2kg', '5kg'];
        
        for ($i = 0; $i < 50; $i++) {
            $orderId = $retailerOrderIds[array_rand($retailerOrderIds)];
            $recipeId = $recipeIds[array_rand($recipeIds)];
            $productName = $productNames[array_rand($productNames)];
            $productVariant = $productVariants[array_rand($productVariants)];
            
            $quantity = rand(10, 100);
            $unitPrice = rand(200, 800);
            $lineTotal = $quantity * $unitPrice;
            
            $orderItems[] = [
                'order_id' => $orderId,
                'recipe_id' => $recipeId,
                'product_name' => $productName,
                'product_variant' => $productVariant,
                'quantity_units' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return RetailerOrderItem::insert($orderItems);
    }

    private function createMessages($users, $companies)
    {
        $messages = [];
        $allUsers = User::all();
        $allCompanies = Company::all();
        
        $messageTypes = ['general', 'order_inquiry', 'quality_feedback', 'delivery_update', 'system_notification'];
        $subjects = [
            'Order Status Update',
            'Quality Feedback Request',
            'Delivery Schedule Confirmation',
            'Pricing Discussion',
            'Product Specification Inquiry',
            'Payment Terms Negotiation',
            'Quality Issue Report',
            'New Order Placement',
            'Inventory Update',
            'Contract Renewal Discussion'
        ];
        
        for ($i = 0; $i < 40; $i++) {
            $sender = $allUsers->random();
            $receiver = $allUsers->where('id', '!=', $sender->id)->random();
            $senderCompany = $allCompanies->random();
            $receiverCompany = $allCompanies->where('company_id', '!=', $senderCompany->company_id)->random();
            
            $messageType = $messageTypes[array_rand($messageTypes)];
            $subject = $subjects[array_rand($subjects)];
            
            $messages[] = [
                'sender_user_id' => $sender->id,
                'receiver_user_id' => $receiver->id,
                'sender_company_id' => $senderCompany->company_id,
                'receiver_company_id' => $receiverCompany->company_id,
                'subject' => $subject,
                'message_body' => 'This is a sample message regarding ' . strtolower($subject) . '. Please review and respond accordingly.',
                'message_type' => $messageType,
                'is_read' => rand(0, 1),
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now()->subDays(rand(1, 30)),
            ];
        }

        return Message::insert($messages);
    }

    private function createInvoices($farmerOrders, $retailerOrders)
    {
        $invoices = [];
        $farmerOrderIds = FarmerOrder::pluck('order_id')->toArray();
        $retailerOrderIds = RetailerOrder::pluck('order_id')->toArray();
        
        $statuses = ['unpaid', 'paid'];
        $customers = ['Green Valley Coffee Farm', 'Mountain View Estate', 'Java Junction Coffee Shop', 'Brew & Bean Café'];
        
        // Create invoices for farmer orders
        for ($i = 0; $i < 15; $i++) {
            $orderId = $farmerOrderIds[array_rand($farmerOrderIds)];
            $order = FarmerOrder::find($orderId);
            $status = $statuses[array_rand($statuses)];
            
            $invoices[] = [
                'number' => 'INV-F' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'date' => Carbon::now()->subDays(rand(1, 30)),
                'customer' => $customers[array_rand($customers)],
                'amount' => $order ? $order->total_amount : rand(5000, 50000),
                'status' => $status,
                'due_date' => Carbon::now()->addDays(rand(15, 45)),
                'paid_at' => $status === 'paid' ? Carbon::now()->subDays(rand(1, 15)) : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        
        // Create invoices for retailer orders
        for ($i = 0; $i < 10; $i++) {
            $orderId = $retailerOrderIds[array_rand($retailerOrderIds)];
            $order = RetailerOrder::find($orderId);
            $status = $statuses[array_rand($statuses)];
            
            $invoices[] = [
                'number' => 'INV-R' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'date' => Carbon::now()->subDays(rand(1, 30)),
                'customer' => $customers[array_rand($customers)],
                'amount' => $order ? $order->total_amount : rand(5000, 50000),
                'status' => $status,
                'due_date' => Carbon::now()->addDays(rand(15, 45)),
                'paid_at' => $status === 'paid' ? Carbon::now()->subDays(rand(1, 15)) : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return Invoice::insert($invoices);
    }

    private function createPayments($invoices)
    {
        $payments = [];
        $invoiceIds = Invoice::pluck('id')->toArray();
        
        $payers = ['Green Valley Coffee Farm', 'Mountain View Estate', 'Java Junction Coffee Shop', 'Brew & Bean Café'];
        $payees = ['Kenya Coffee Processors Ltd', 'Premium Roasters Kenya', 'Artisan Coffee Works'];
        $methods = ['bank_transfer', 'mobile_money', 'cash', 'cheque', 'credit_card'];
        $statuses = ['pending', 'completed', 'refunded'];
        
        for ($i = 0; $i < 30; $i++) {
            $invoiceId = $invoiceIds[array_rand($invoiceIds)];
            $invoice = Invoice::find($invoiceId);
            
            $payments[] = [
                'date' => Carbon::now()->subDays(rand(1, 30)),
                'payer' => $payers[array_rand($payers)],
                'payee' => $payees[array_rand($payees)],
                'amount' => $invoice ? $invoice->amount : rand(5000, 50000),
                'method' => $methods[array_rand($methods)],
                'status' => $statuses[array_rand($statuses)],
                'invoice_id' => $invoiceId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return Payment::insert($payments);
    }
} 