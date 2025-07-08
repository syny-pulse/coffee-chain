<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pricing;
use App\Models\Company;

class PricingSeeder extends Seeder
{
    public function run()
    {
        // Get all farmer companies
        $farmerCompanies = Company::where('company_type', 'farmer')->get();

        foreach ($farmerCompanies as $company) {
            // Create pricing for different coffee varieties and grades
            $pricingData = [
                // Arabica pricing
                ['coffee_variety' => 'arabica', 'grade' => 'grade_1', 'unit_price' => 8500.00],
                ['coffee_variety' => 'arabica', 'grade' => 'grade_2', 'unit_price' => 7500.00],
                ['coffee_variety' => 'arabica', 'grade' => 'grade_3', 'unit_price' => 6500.00],
                ['coffee_variety' => 'arabica', 'grade' => 'grade_4', 'unit_price' => 5500.00],
                ['coffee_variety' => 'arabica', 'grade' => 'grade_5', 'unit_price' => 4500.00],
                
                // Robusta pricing
                ['coffee_variety' => 'robusta', 'grade' => 'grade_1', 'unit_price' => 6500.00],
                ['coffee_variety' => 'robusta', 'grade' => 'grade_2', 'unit_price' => 5500.00],
                ['coffee_variety' => 'robusta', 'grade' => 'grade_3', 'unit_price' => 4500.00],
                ['coffee_variety' => 'robusta', 'grade' => 'grade_4', 'unit_price' => 3500.00],
                ['coffee_variety' => 'robusta', 'grade' => 'grade_5', 'unit_price' => 2500.00],
            ];

            foreach ($pricingData as $pricing) {
                Pricing::updateOrCreate(
                    [
                        'company_id' => $company->company_id,
                        'coffee_variety' => $pricing['coffee_variety'],
                        'grade' => $pricing['grade'],
                    ],
                    [
                        'unit_price' => $pricing['unit_price'],
                    ]
                );
            }
        }

        $this->command->info('Pricing data seeded successfully!');
    }
} 