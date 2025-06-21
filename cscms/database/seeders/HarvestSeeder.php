<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HarvestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get farmer company IDs
        $farmerCompanyIds = DB::table('companies')
            ->where('company_type', 'farmer')
            ->pluck('company_id')
            ->toArray();

        if (empty($farmerCompanyIds)) {
            $this->command->warn('No farmer companies found. Please run CompanySeeder first.');
            return;
        }

        $harvests = [
            // Recent harvests
            [
                'company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'coffee_variety' => 'arabica',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'quantity_kg' => 500.00,
                'available_quantity_kg' => 400.00,
                'harvest_date' => Carbon::now()->subDays(5),
                'availability_status' => 'available',
                'quality_notes' => 'Excellent quality with bright acidity and floral notes. Perfect for specialty coffee.',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'coffee_variety' => 'arabica',
                'processing_method' => 'natural',
                'grade' => 'grade_2',
                'quantity_kg' => 300.00,
                'available_quantity_kg' => 250.00,
                'harvest_date' => Carbon::now()->subDays(8),
                'availability_status' => 'available',
                'quality_notes' => 'Good body with chocolate notes. Suitable for medium roasts.',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'coffee_variety' => 'robusta',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'quantity_kg' => 400.00,
                'available_quantity_kg' => 320.00,
                'harvest_date' => Carbon::now()->subDays(10),
                'availability_status' => 'available',
                'quality_notes' => 'High caffeine content with strong body. Ideal for espresso blends.',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'company_id' => $farmerCompanyIds[1], // Sunrise Agricultural Co.
                'coffee_variety' => 'arabica',
                'processing_method' => 'honey',
                'grade' => 'grade_1',
                'quantity_kg' => 350.00,
                'available_quantity_kg' => 280.00,
                'harvest_date' => Carbon::now()->subDays(3),
                'availability_status' => 'available',
                'quality_notes' => 'Unique honey processing gives sweet, complex flavor profile.',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'company_id' => $farmerCompanyIds[1], // Sunrise Agricultural Co.
                'coffee_variety' => 'robusta',
                'processing_method' => 'natural',
                'grade' => 'grade_2',
                'quantity_kg' => 250.00,
                'available_quantity_kg' => 200.00,
                'harvest_date' => Carbon::now()->subDays(7),
                'availability_status' => 'available',
                'quality_notes' => 'Standard quality robusta with earthy notes.',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'company_id' => $farmerCompanyIds[2], // Harvest Moon Organics
                'coffee_variety' => 'arabica',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'quantity_kg' => 450.00,
                'available_quantity_kg' => 380.00,
                'harvest_date' => Carbon::now()->subDays(2),
                'availability_status' => 'available',
                'quality_notes' => 'Organic certified. Clean, bright acidity with citrus notes.',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            // Historical harvests
            [
                'company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'coffee_variety' => 'arabica',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'quantity_kg' => 480.00,
                'available_quantity_kg' => 0.00,
                'harvest_date' => Carbon::now()->subDays(30),
                'availability_status' => 'sold_out',
                'quality_notes' => 'Previous harvest - all sold to premium buyers.',
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'company_id' => $farmerCompanyIds[1], // Sunrise Agricultural Co.
                'coffee_variety' => 'robusta',
                'processing_method' => 'washed',
                'grade' => 'grade_1',
                'quantity_kg' => 380.00,
                'available_quantity_kg' => 0.00,
                'harvest_date' => Carbon::now()->subDays(25),
                'availability_status' => 'sold_out',
                'quality_notes' => 'High demand robusta - excellent for commercial use.',
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            // Reserved harvests
            [
                'company_id' => $farmerCompanyIds[2], // Harvest Moon Organics
                'coffee_variety' => 'arabica',
                'processing_method' => 'natural',
                'grade' => 'grade_2',
                'quantity_kg' => 200.00,
                'available_quantity_kg' => 0.00,
                'harvest_date' => Carbon::now()->subDays(12),
                'availability_status' => 'reserved',
                'quality_notes' => 'Reserved for upcoming order. Good body and sweetness.',
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(5),
            ],
        ];

        DB::table('farmer_harvest')->insert($harvests);
        
        $this->command->info('Harvest data seeded successfully!');
    }
} 