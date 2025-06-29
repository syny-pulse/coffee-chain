<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get farmer and processor company IDs
        $farmerCompanyIds = DB::table('companies')
            ->where('company_type', 'farmer')
            ->pluck('company_id')
            ->toArray();

        $processorCompanyIds = DB::table('companies')
            ->where('company_type', 'processor')
            ->pluck('company_id')
            ->toArray();

        if (empty($farmerCompanyIds) || empty($processorCompanyIds)) {
            $this->command->warn('No farmer or processor companies found. Please run CompanySeeder first.');
            return;
        }

        $orders = [
            // Recent orders
            [
                'processor_company_id' => $processorCompanyIds[0], // First processor company
                'farmer_company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'coffee_variety' => 'arabica',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'quantity_kg' => 200.00,
                'unit_price' => 15.00,
                'total_amount' => 3000.00,
                'expected_delivery_date' => Carbon::now()->addDays(7),
                'actual_delivery_date' => null,
                'order_status' => 'confirmed',
                'notes' => 'Premium arabica for specialty coffee production. High quality requirements.',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'processor_company_id' => $processorCompanyIds[0], // First processor company
                'farmer_company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'coffee_variety' => 'robusta',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'quantity_kg' => 150.00,
                'unit_price' => 10.00,
                'total_amount' => 1500.00,
                'expected_delivery_date' => Carbon::now()->addDays(5),
                'actual_delivery_date' => null,
                'order_status' => 'processing',
                'notes' => 'Robusta for espresso blend. Need high caffeine content.',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'processor_company_id' => $processorCompanyIds[0], // First processor company
                'farmer_company_id' => $farmerCompanyIds[1], // Sunrise Agricultural Co.
                'coffee_variety' => 'arabica',
                'processing_method' => 'honey',
                'grade' => 'grade_1',
                'quantity_kg' => 100.00,
                'unit_price' => 16.50,
                'total_amount' => 1650.00,
                'expected_delivery_date' => Carbon::now()->addDays(10),
                'actual_delivery_date' => null,
                'order_status' => 'pending',
                'notes' => 'Honey processed arabica for experimental roasting.',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'processor_company_id' => $processorCompanyIds[0], // First processor company
                'farmer_company_id' => $farmerCompanyIds[2], // Harvest Moon Organics
                'coffee_variety' => 'arabica',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'quantity_kg' => 180.00,
                'unit_price' => 17.00,
                'total_amount' => 3060.00,
                'expected_delivery_date' => Carbon::now()->addDays(8),
                'actual_delivery_date' => null,
                'order_status' => 'confirmed',
                'notes' => 'Organic certified arabica. Premium pricing for organic market.',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            // Completed orders
            [
                'processor_company_id' => $processorCompanyIds[0], // First processor company
                'farmer_company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'coffee_variety' => 'arabica',
                'processing_method' => 'natural',
                'grade' => 'grade_2',
                'quantity_kg' => 120.00,
                'unit_price' => 12.50,
                'total_amount' => 1500.00,
                'expected_delivery_date' => Carbon::now()->subDays(5),
                'actual_delivery_date' => Carbon::now()->subDays(4),
                'order_status' => 'delivered',
                'notes' => 'Successfully delivered. Quality met expectations.',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'processor_company_id' => $processorCompanyIds[0], // First processor company
                'farmer_company_id' => $farmerCompanyIds[1], // Sunrise Agricultural Co.
                'coffee_variety' => 'robusta',
                'processing_method' => 'natural',
                'grade' => 'grade_2',
                'quantity_kg' => 200.00,
                'unit_price' => 8.50,
                'total_amount' => 1700.00,
                'expected_delivery_date' => Carbon::now()->subDays(8),
                'actual_delivery_date' => Carbon::now()->subDays(7),
                'order_status' => 'delivered',
                'notes' => 'Delivered on time. Good quality for commercial use.',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            // Shipped orders
            [
                'processor_company_id' => $processorCompanyIds[0], // First processor company
                'farmer_company_id' => $farmerCompanyIds[2], // Harvest Moon Organics
                'coffee_variety' => 'arabica',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'quantity_kg' => 90.00,
                'unit_price' => 16.00,
                'total_amount' => 1440.00,
                'expected_delivery_date' => Carbon::now()->addDays(2),
                'actual_delivery_date' => null,
                'order_status' => 'shipped',
                'notes' => 'Shipped via express delivery. Organic certification included.',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            // Cancelled order
            [
                'processor_company_id' => $processorCompanyIds[0], // First processor company
                'farmer_company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'coffee_variety' => 'robusta',
                'processing_method' => 'washed',
                'grade' => 'grade_3',
                'quantity_kg' => 80.00,
                'unit_price' => 7.50,
                'total_amount' => 600.00,
                'expected_delivery_date' => Carbon::now()->subDays(3),
                'actual_delivery_date' => null,
                'order_status' => 'cancelled',
                'notes' => 'Cancelled due to quality concerns. Will re-harvest.',
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(3),
            ],
        ];

        DB::table('farmer_orders')->insert($orders);
        
        $this->command->info('Order data seeded successfully!');
    }
} 