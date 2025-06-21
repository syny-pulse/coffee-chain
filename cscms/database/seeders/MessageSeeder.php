<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get company and user IDs
        $farmerCompanyIds = DB::table('companies')
            ->where('company_type', 'farmer')
            ->pluck('company_id')
            ->toArray();

        $processorCompanyIds = DB::table('companies')
            ->where('company_type', 'processor')
            ->pluck('company_id')
            ->toArray();

        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($farmerCompanyIds) || empty($processorCompanyIds) || empty($userIds)) {
            $this->command->warn('No companies or users found. Please run UserSeeder and CompanySeeder first.');
            return;
        }

        $messages = [
            // Order inquiries
            [
                'sender_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'receiver_company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'sender_user_id' => $userIds[0],
                'receiver_user_id' => $userIds[1],
                'subject' => 'Order Inquiry - Premium Arabica',
                'message_body' => 'We are interested in purchasing 200kg of premium Arabica beans for our specialty coffee line. Please let us know your current availability and pricing.',
                'message_type' => 'order_inquiry',
                'is_read' => false,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'sender_company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'receiver_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'sender_user_id' => $userIds[1],
                'receiver_user_id' => $userIds[0],
                'subject' => 'Re: Order Inquiry - Premium Arabica',
                'message_body' => 'Thank you for your inquiry. We have 400kg of Grade 1 Arabica available at $15.00/kg. We can deliver within 7 days. Would you like to proceed with the order?',
                'message_type' => 'order_inquiry',
                'is_read' => true,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            // Quality feedback
            [
                'sender_company_id' => $processorCompanyIds[1], // East African Mills
                'receiver_company_id' => $farmerCompanyIds[1], // Sunrise Agricultural Co.
                'sender_user_id' => $userIds[0],
                'receiver_user_id' => $userIds[2],
                'subject' => 'Quality Feedback - Recent Shipment',
                'message_body' => 'Excellent quality on the last shipment of Robusta beans. The caffeine content and body are perfect for our espresso blends. Looking forward to more business.',
                'message_type' => 'quality_feedback',
                'is_read' => true,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            // Delivery updates
            [
                'sender_company_id' => $farmerCompanyIds[2], // Harvest Moon Organics
                'receiver_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'sender_user_id' => $userIds[2],
                'receiver_user_id' => $userIds[0],
                'subject' => 'Delivery Update - Organic Arabica',
                'message_body' => 'Your order of 180kg organic Arabica has been processed and will be shipped tomorrow. Expected delivery date is within 3 business days. Tracking number will be provided.',
                'message_type' => 'delivery_update',
                'is_read' => false,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            // General communication
            [
                'sender_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'receiver_company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'sender_user_id' => $userIds[0],
                'receiver_user_id' => $userIds[1],
                'subject' => 'Partnership Discussion',
                'message_body' => 'We would like to discuss a long-term partnership for regular supply of premium coffee beans. Can we schedule a meeting next week to discuss terms and conditions?',
                'message_type' => 'general',
                'is_read' => false,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            // System notifications
            [
                'sender_company_id' => $processorCompanyIds[1], // East African Mills
                'receiver_company_id' => $farmerCompanyIds[1], // Sunrise Agricultural Co.
                'sender_user_id' => $userIds[0],
                'receiver_user_id' => $userIds[2],
                'subject' => 'Payment Confirmation',
                'message_body' => 'Payment of $1,700 for your recent Robusta shipment has been processed and will be credited to your account within 2 business days. Thank you for your business.',
                'message_type' => 'system_notification',
                'is_read' => true,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            // Price negotiations
            [
                'sender_company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'receiver_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'sender_user_id' => $userIds[1],
                'receiver_user_id' => $userIds[0],
                'subject' => 'Price Negotiation - Bulk Order',
                'message_body' => 'For bulk orders of 500kg or more, we can offer a 5% discount on our current prices. This would apply to both Arabica and Robusta varieties. Please let us know if you are interested.',
                'message_type' => 'general',
                'is_read' => false,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6),
            ],
            // Quality concerns
            [
                'sender_company_id' => $processorCompanyIds[0], // Uganda Food Processing Ltd
                'receiver_company_id' => $farmerCompanyIds[0], // Green Valley Farms
                'sender_user_id' => $userIds[0],
                'receiver_user_id' => $userIds[1],
                'subject' => 'Quality Issue - Recent Shipment',
                'message_body' => 'We noticed some moisture content issues in the recent shipment. Could you please check your storage conditions and let us know how we can prevent this in future deliveries?',
                'message_type' => 'quality_feedback',
                'is_read' => true,
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            // New product inquiries
            [
                'sender_company_id' => $processorCompanyIds[1], // East African Mills
                'receiver_company_id' => $farmerCompanyIds[2], // Harvest Moon Organics
                'sender_user_id' => $userIds[0],
                'receiver_user_id' => $userIds[2],
                'subject' => 'Inquiry - Honey Processed Coffee',
                'message_body' => 'We are interested in trying honey processed coffee for our new product line. Do you have any available or can you produce some for us?',
                'message_type' => 'order_inquiry',
                'is_read' => false,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            // Contract discussions
            [
                'sender_company_id' => $farmerCompanyIds[1], // Sunrise Agricultural Co.
                'receiver_company_id' => $processorCompanyIds[1], // East African Mills
                'sender_user_id' => $userIds[2],
                'receiver_user_id' => $userIds[0],
                'subject' => 'Contract Renewal Discussion',
                'message_body' => 'Our current supply contract is up for renewal next month. We would like to discuss terms and potentially increase our supply volume. When would be a good time to meet?',
                'message_type' => 'general',
                'is_read' => false,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ];

        DB::table('messages')->insert($messages);
        
        $this->command->info('Message data seeded successfully!');
    }
} 