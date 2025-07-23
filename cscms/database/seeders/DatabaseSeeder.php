<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Use the comprehensive seeder for complete data
        $this->call([
            ComprehensiveCoffeeChainSeeder::class,
        ]);
        
        // Alternatively, you can use individual seeders if needed
        // $this->call([
        //     UserSeeder::class,
        //     CompanySeeder::class,
        //     HarvestSeeder::class,
        //     OrderSeeder::class,
        //     MessageSeeder::class,
        //     EmployeeSeeder::class,
        //     InventorySeeder::class,
        //     PricingSeeder::class,
        //     ProcessorSeeder::class,
        // ]);
    }
}
