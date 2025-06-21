<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
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

        $employees = [
            // Uganda Food Processing Ltd employees
            [
                'processor_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'employee_name' => 'Sarah Nakimera',
                'employee_code' => 'UFP001',
                'skill_set' => 'quality_control',
                'primary_station' => 'quality_control',
                'current_station' => 'quality_control',
                'availability_status' => 'available',
                'shift_schedule' => 'morning',
                'hourly_rate' => 15000.00,
                'hire_date' => Carbon::now()->subYears(3),
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(3),
                'updated_at' => Carbon::now(),
            ],
            [
                'processor_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'employee_name' => 'David Mukisa',
                'employee_code' => 'UFP002',
                'skill_set' => 'roasting',
                'primary_station' => 'roasting',
                'current_station' => 'roasting',
                'availability_status' => 'available',
                'shift_schedule' => 'morning',
                'hourly_rate' => 12000.00,
                'hire_date' => Carbon::now()->subYears(2),
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(2),
                'updated_at' => Carbon::now(),
            ],
            [
                'processor_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'employee_name' => 'Grace Nalukenge',
                'employee_code' => 'UFP003',
                'skill_set' => 'packaging',
                'primary_station' => 'packaging',
                'current_station' => 'packaging',
                'availability_status' => 'available',
                'shift_schedule' => 'afternoon',
                'hourly_rate' => 8000.00,
                'hire_date' => Carbon::now()->subMonths(6),
                'status' => 'active',
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now(),
            ],
            [
                'processor_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'employee_name' => 'John Kato',
                'employee_code' => 'UFP004',
                'skill_set' => 'logistics',
                'primary_station' => 'logistics',
                'current_station' => 'logistics',
                'availability_status' => 'available',
                'shift_schedule' => 'morning',
                'hourly_rate' => 10000.00,
                'hire_date' => Carbon::now()->subYears(1),
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(1),
                'updated_at' => Carbon::now(),
            ],

            // East African Mills employees
            [
                'processor_company_id' => $processorCompanyIds[1], // East African Mills
                'employee_name' => 'Mary Wanjiku',
                'employee_code' => 'EAM001',
                'skill_set' => 'quality_control',
                'primary_station' => 'quality_control',
                'current_station' => 'quality_control',
                'availability_status' => 'available',
                'shift_schedule' => 'morning',
                'hourly_rate' => 20000.00,
                'hire_date' => Carbon::now()->subYears(4),
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(4),
                'updated_at' => Carbon::now(),
            ],
            [
                'processor_company_id' => $processorCompanyIds[1], // East African Mills
                'employee_name' => 'Peter Ochieng',
                'employee_code' => 'EAM002',
                'skill_set' => 'roasting',
                'primary_station' => 'roasting',
                'current_station' => 'roasting',
                'availability_status' => 'available',
                'shift_schedule' => 'morning',
                'hourly_rate' => 15000.00,
                'hire_date' => Carbon::now()->subYears(2),
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(2),
                'updated_at' => Carbon::now(),
            ],
            [
                'processor_company_id' => $processorCompanyIds[1], // East African Mills
                'employee_name' => 'Faith Akinyi',
                'employee_code' => 'EAM003',
                'skill_set' => 'packaging',
                'primary_station' => 'packaging',
                'current_station' => 'packaging',
                'availability_status' => 'available',
                'shift_schedule' => 'afternoon',
                'hourly_rate' => 9000.00,
                'hire_date' => Carbon::now()->subMonths(8),
                'status' => 'active',
                'created_at' => Carbon::now()->subMonths(8),
                'updated_at' => Carbon::now(),
            ],
            [
                'processor_company_id' => $processorCompanyIds[1], // East African Mills
                'employee_name' => 'James Odhiambo',
                'employee_code' => 'EAM004',
                'skill_set' => 'maintenance',
                'primary_station' => 'maintenance',
                'current_station' => 'maintenance',
                'availability_status' => 'available',
                'shift_schedule' => 'flexible',
                'hourly_rate' => 8500.00,
                'hire_date' => Carbon::now()->subYears(1),
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(1),
                'updated_at' => Carbon::now(),
            ],

            // Golden Grain Processors employees (rejected company but still has employees)
            [
                'processor_company_id' => $processorCompanyIds[2], // Golden Grain Processors
                'employee_name' => 'Robert Mugisha',
                'employee_code' => 'GGP001',
                'skill_set' => 'quality_control',
                'primary_station' => 'quality_control',
                'current_station' => null,
                'availability_status' => 'off_duty',
                'shift_schedule' => 'morning',
                'hourly_rate' => 18000.00,
                'hire_date' => Carbon::now()->subYears(2),
                'status' => 'inactive',
                'created_at' => Carbon::now()->subYears(2),
                'updated_at' => Carbon::now(),
            ],
            [
                'processor_company_id' => $processorCompanyIds[2], // Golden Grain Processors
                'employee_name' => 'Alice Nabukenya',
                'employee_code' => 'GGP002',
                'skill_set' => 'quality_control',
                'primary_station' => 'quality_control',
                'current_station' => null,
                'availability_status' => 'off_duty',
                'shift_schedule' => 'morning',
                'hourly_rate' => 12000.00,
                'hire_date' => Carbon::now()->subYears(1),
                'status' => 'inactive',
                'created_at' => Carbon::now()->subYears(1),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('employees')->insert($employees);
        
        $this->command->info('Employee data seeded successfully!');
    }
} 