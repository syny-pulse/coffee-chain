<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProcessorSeeder extends Seeder
{
    public function run(): void
    {
        // Create the default processor company
        $company = Company::create([
            'company_name' => 'Coffee Chain Processor',
            'email' => 'processor@coffeechain.com',
            'company_type' => 'processor',
            'phone' => '+256700000000',
            'address' => 'Kampala, Uganda',
            'registration_number' => 'PROC001',
            'acceptance_status' => 'accepted',
            'pdf_path' => 'documents/processor/registration.pdf',
            'financial_risk_rating' => 0.0,
            'reputational_risk_rating' => 0.0,
            'compliance_risk_rating' => 0.0,
        ]);

        // Create the default processor user
        User::create([
            'name' => 'Processor Admin',
            'email' => 'processor@coffeechain.com',
            'password' => Hash::make('processor123'), // Default password
            'user_type' => 'processor',
            'company_id' => $company->company_id,
            'phone' => '+256700000000',
            'address' => 'Kampala, Uganda',
            'status' => 'active',
        ]);
    }
} 