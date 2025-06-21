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
    // In database/seeders/DatabaseSeeder.php
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            HarvestSeeder::class,
            OrderSeeder::class,
            MessageSeeder::class,
            EmployeeSeeder::class,
            InventorySeeder::class,
        ]);

        $this->call([
            ProcessorSeeder::class,
        ]);
    }
}
