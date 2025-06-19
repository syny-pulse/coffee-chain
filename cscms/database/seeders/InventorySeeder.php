<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get processor company IDs
        $processorCompanyIds = DB::table('companies')
            ->where('company_type', 'processor')
            ->pluck('company_id')
            ->toArray();

        if (empty($processorCompanyIds)) {
            $this->command->warn('No processor companies found. Please run CompanySeeder first.');
            return;
        }

        $rawMaterialInventory = [
            // Uganda Food Processing Ltd inventory
            [
                'processor_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'coffee_variety' => 'arabica',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'current_stock_kg' => 1500.00,
                'reserved_stock_kg' => 300.00,
                'available_stock_kg' => 1200.00,
                'average_cost_per_kg' => 14.50,
                'last_updated' => Carbon::now()->subHours(2),
            ],
            [
                'processor_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'coffee_variety' => 'arabica',
                'processing_method' => 'natural',
                'grade' => 'grade_2',
                'current_stock_kg' => 800.00,
                'reserved_stock_kg' => 150.00,
                'available_stock_kg' => 650.00,
                'average_cost_per_kg' => 12.00,
                'last_updated' => Carbon::now()->subHours(4),
            ],
            [
                'processor_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'coffee_variety' => 'robusta',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'current_stock_kg' => 2200.00,
                'reserved_stock_kg' => 500.00,
                'available_stock_kg' => 1700.00,
                'average_cost_per_kg' => 9.50,
                'last_updated' => Carbon::now()->subHours(1),
            ],
            [
                'processor_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'coffee_variety' => 'robusta',
                'processing_method' => 'natural',
                'grade' => 'grade_2',
                'current_stock_kg' => 1200.00,
                'reserved_stock_kg' => 200.00,
                'available_stock_kg' => 1000.00,
                'average_cost_per_kg' => 8.00,
                'last_updated' => Carbon::now()->subHours(6),
            ],

            // East African Mills inventory
            [
                'processor_company_id' => $processorCompanyIds[1], // East African Mills
                'coffee_variety' => 'arabica',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'current_stock_kg' => 2000.00,
                'reserved_stock_kg' => 400.00,
                'available_stock_kg' => 1600.00,
                'average_cost_per_kg' => 15.20,
                'last_updated' => Carbon::now()->subHours(3),
            ],
            [
                'processor_company_id' => $processorCompanyIds[1], // East African Mills
                'coffee_variety' => 'arabica',
                'processing_method' => 'honey',
                'grade' => 'grade_1',
                'current_stock_kg' => 600.00,
                'reserved_stock_kg' => 100.00,
                'available_stock_kg' => 500.00,
                'average_cost_per_kg' => 16.50,
                'last_updated' => Carbon::now()->subHours(5),
            ],
            [
                'processor_company_id' => $processorCompanyIds[1], // East African Mills
                'coffee_variety' => 'robusta',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'current_stock_kg' => 1800.00,
                'reserved_stock_kg' => 350.00,
                'available_stock_kg' => 1450.00,
                'average_cost_per_kg' => 9.80,
                'last_updated' => Carbon::now()->subHours(2),
            ],
            [
                'processor_company_id' => $processorCompanyIds[1], // East African Mills
                'coffee_variety' => 'robusta',
                'processing_method' => 'natural',
                'grade' => 'grade_2',
                'current_stock_kg' => 900.00,
                'reserved_stock_kg' => 180.00,
                'available_stock_kg' => 720.00,
                'average_cost_per_kg' => 8.20,
                'last_updated' => Carbon::now()->subHours(8),
            ],

            // Golden Grain Processors inventory (rejected company)
            [
                'processor_company_id' => $processorCompanyIds[2], // Golden Grain Processors
                'coffee_variety' => 'arabica',
                'processing_method' => 'washed',
                'grade' => 'grade_2',
                'current_stock_kg' => 500.00,
                'reserved_stock_kg' => 0.00,
                'available_stock_kg' => 500.00,
                'average_cost_per_kg' => 11.50,
                'last_updated' => Carbon::now()->subDays(5),
            ],
            [
                'processor_company_id' => $processorCompanyIds[2], // Golden Grain Processors
                'coffee_variety' => 'robusta',
                'processing_method' => 'washed',
                'grade' => 'grade_3',
                'current_stock_kg' => 800.00,
                'reserved_stock_kg' => 0.00,
                'available_stock_kg' => 800.00,
                'average_cost_per_kg' => 7.50,
                'last_updated' => Carbon::now()->subDays(5),
            ],
        ];

        DB::table('processor_raw_material_inventory')->insert($rawMaterialInventory);
        
        $this->command->info('Raw material inventory data seeded successfully!');
    }
} 