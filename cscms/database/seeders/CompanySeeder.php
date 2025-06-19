<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            // Farmers
            [
                'company_name' => 'Green Valley Farms',
                'email' => 'info@greenvalleyfarms.com',
                'company_type' => 'farmer',
                'phone' => '+256-700-123456',
                'address' => 'Plot 15, Mukono District, Central Region, Uganda',
                'registration_number' => 'UG-FARM-001234',
                'acceptance_status' => 'accepted',
                'financial_risk_rating' => 2.5,
                'reputational_risk_rating' => 1.8,
                'compliance_risk_rating' => 2.0,
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'company_name' => 'Sunrise Agricultural Co.',
                'email' => 'contact@sunriseagri.ug',
                'company_type' => 'farmer',
                'phone' => '+256-701-234567',
                'address' => 'Masaka Road, Wakiso District, Central Region, Uganda',
                'registration_number' => 'UG-FARM-002345',
                'acceptance_status' => 'pending',
                'financial_risk_rating' => 3.2,
                'reputational_risk_rating' => 2.1,
                'compliance_risk_rating' => 2.8,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'company_name' => 'Harvest Moon Organics',
                'email' => 'admin@harvestmoon.co.ug',
                'company_type' => 'farmer',
                'phone' => '+256-702-345678',
                'address' => 'Jinja District, Eastern Region, Uganda',
                'registration_number' => 'UG-FARM-003456',
                'acceptance_status' => 'visit_scheduled',
                'financial_risk_rating' => 1.9,
                'reputational_risk_rating' => 1.5,
                'compliance_risk_rating' => 1.7,
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(1),
            ],

            // Processors
            [
                'company_name' => 'Uganda Food Processing Ltd',
                'email' => 'operations@ufp.co.ug',
                'company_type' => 'processor',
                'phone' => '+256-703-456789',
                'address' => 'Industrial Area, Kampala, Central Region, Uganda',
                'registration_number' => 'UG-PROC-001234',
                'acceptance_status' => 'accepted',
                'financial_risk_rating' => 2.8,
                'reputational_risk_rating' => 2.3,
                'compliance_risk_rating' => 2.5,
                'created_at' => Carbon::now()->subDays(45),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'company_name' => 'East African Mills',
                'email' => 'info@eamills.com',
                'company_type' => 'processor',
                'phone' => '+256-704-567890',
                'address' => 'Mbale Industrial Park, Eastern Region, Uganda',
                'registration_number' => 'UG-PROC-002345',
                'acceptance_status' => 'accepted',
                'financial_risk_rating' => 1.8,
                'reputational_risk_rating' => 1.6,
                'compliance_risk_rating' => 1.9,
                'created_at' => Carbon::now()->subDays(60),
                'updated_at' => Carbon::now()->subDays(20),
            ],
            [
                'company_name' => 'Golden Grain Processors',
                'email' => 'management@goldengrain.ug',
                'company_type' => 'processor',
                'phone' => '+256-705-678901',
                'address' => 'Mbarara Town, Western Region, Uganda',
                'registration_number' => 'UG-PROC-003456',
                'acceptance_status' => 'rejected',
                'financial_risk_rating' => 4.2,
                'reputational_risk_rating' => 3.8,
                'compliance_risk_rating' => 4.0,
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now()->subDays(3),
            ],

            // Retailers
            [
                'company_name' => 'Fresh Market Supermarkets',
                'email' => 'procurement@freshmarket.ug',
                'company_type' => 'retailer',
                'phone' => '+256-706-789012',
                'address' => 'Garden City Mall, Kampala, Central Region, Uganda',
                'registration_number' => 'UG-RET-001234',
                'acceptance_status' => 'accepted',
                'financial_risk_rating' => 2.1,
                'reputational_risk_rating' => 1.9,
                'compliance_risk_rating' => 2.0,
                'created_at' => Carbon::now()->subDays(50),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'company_name' => 'Quality Foods Chain',
                'email' => 'suppliers@qualityfoods.co.ug',
                'company_type' => 'retailer',
                'phone' => '+256-707-890123',
                'address' => 'Acacia Mall, Kampala, Central Region, Uganda',
                'registration_number' => 'UG-RET-002345',
                'acceptance_status' => 'pending',
                'financial_risk_rating' => 2.7,
                'reputational_risk_rating' => 2.4,
                'compliance_risk_rating' => 2.6,
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'company_name' => 'Village Market Ltd',
                'email' => 'info@villagemarket.ug',
                'company_type' => 'retailer',
                'phone' => '+256-708-901234',
                'address' => 'Ntinda Shopping Center, Kampala, Central Region, Uganda',
                'registration_number' => 'UG-RET-003456',
                'acceptance_status' => 'visit_scheduled',
                'financial_risk_rating' => 3.1,
                'reputational_risk_rating' => 2.8,
                'compliance_risk_rating' => 3.0,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now(),
            ],
            [
                'company_name' => 'Metro Wholesale Foods',
                'email' => 'wholesale@metrofoods.ug',
                'company_type' => 'retailer',
                'phone' => '+256-709-012345',
                'address' => 'Nakawa Industrial Area, Kampala, Central Region, Uganda',
                'registration_number' => 'UG-RET-004567',
                'acceptance_status' => 'accepted',
                'financial_risk_rating' => 1.7,
                'reputational_risk_rating' => 1.4,
                'compliance_risk_rating' => 1.6,
                'created_at' => Carbon::now()->subDays(35),
                'updated_at' => Carbon::now()->subDays(8),
            ],

            // Additional companies with different statuses for testing
            [
                'company_name' => 'New Venture Farms',
                'email' => 'startup@newventure.ug',
                'company_type' => 'farmer',
                'phone' => '+256-710-123456',
                'address' => 'Gulu District, Northern Region, Uganda',
                'registration_number' => 'UG-FARM-004567',
                'acceptance_status' => 'pending',
                'financial_risk_rating' => 0.0,
                'reputational_risk_rating' => 0.0,
                'compliance_risk_rating' => 0.0,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'company_name' => 'Failed Processing Co.',
                'email' => 'contact@failedprocessing.ug',
                'company_type' => 'processor',
                'phone' => '+256-711-234567',
                'address' => 'Soroti District, Eastern Region, Uganda',
                'registration_number' => 'UG-PROC-004567',
                'acceptance_status' => 'rejected',
                'financial_risk_rating' => 4.8,
                'reputational_risk_rating' => 4.5,
                'compliance_risk_rating' => 4.7,
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(5),
            ],
        ];

        DB::table('companies')->insert($companies);
    }
}