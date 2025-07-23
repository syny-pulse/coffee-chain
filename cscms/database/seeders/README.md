# Comprehensive Coffee Chain Seeder

## Overview
The `ComprehensiveCoffeeChainSeeder` is a comprehensive database seeder that generates realistic and logically connected data for the Coffee Supply Chain Management System (CSCMS). It creates more than 10+ values in each table with proper relationships and business logic.

## Features

### 1. **Companies (15 companies)**
- **5 Farmers**: Green Valley Coffee Farm, Mountain View Estate, Highland Coffee Growers, Arabica Valley Farm, Robusta Hills Estate
- **5 Processors**: Kenya Coffee Processors Ltd, Premium Roasters Kenya, Artisan Coffee Works, Mombasa Coffee Millers, Central Kenya Processors
- **5 Retailers**: Java Junction Coffee Shop, Brew & Bean Café, Coffee Culture Kenya, Mocha Moments, Espresso Express

### 2. **Users (20+ users)**
- 1 System Administrator
- 5 Farmer managers (one per farmer company)
- 5 Processor managers (one per processor company)
- 5 Retailer managers (one per retailer company)
- All users have realistic email addresses and contact information

### 3. **Product Recipes (15 recipes)**
- **Drinking Coffee**: Classic Espresso, Smooth Latte, Rich Cappuccino, Bold Americano
- **Roasted Coffee**: Light Roast Arabica, Medium Roast Blend, Dark Roast Robusta, Premium Single Origin
- **Coffee Scents**: Morning Brew Scent, Roasted Bean Aroma, Vanilla Coffee Blend
- **Coffee Soap**: Exfoliating Coffee Soap, Luxury Coffee Soap, Organic Coffee Soap

### 4. **Products (25+ products)**
- Various coffee varieties (Arabica, Robusta)
- Different processing methods (Natural, Washed, Honey)
- Multiple roast levels (Light, Medium, Dark, Espresso, French)
- Quality grades (Grade 1-5)
- Realistic pricing and quantities

### 5. **Employees (15+ employees)**
- 3 employees per processor company
- Various skill sets: Grading, Roasting, Packaging, Logistics, Quality Control, Maintenance
- Different stations and availability statuses
- Realistic hourly rates and hire dates

### 6. **Farmer Harvests (20+ harvests)**
- 4 harvests per farmer company
- Different coffee varieties, processing methods, and grades
- Realistic quantities and availability statuses
- Quality notes for each harvest

### 7. **Processor Raw Material Inventory (15+ inventory items)**
- Complete inventory for all processor companies
- All combinations of coffee varieties, processing methods, and grades
- Realistic stock levels, reserved stock, and available stock
- Average cost per kg calculations

### 8. **Retailer Inventory (20+ inventory items)**
- Various product types (drinking coffee, roasted coffee, coffee scents, coffee soap)
- Different coffee breeds and roast grades
- Realistic quantities for retail operations

### 9. **Pricing (20+ pricing records)**
- Pricing for all companies
- All combinations of coffee varieties and grades
- Realistic unit prices

### 10. **Farmer Orders (25+ orders)**
- Orders from processors to farmers
- Realistic quantities, pricing, and delivery dates
- Various order statuses (pending, confirmed, in_production, shipped, delivered, cancelled)
- Employee assignments for order management

### 11. **Retailer Orders (30+ orders)**
- Orders from retailers to processors
- Order numbers, total amounts, and delivery schedules
- Various order statuses and shipping addresses
- Employee assignments for order processing

### 12. **Retailer Order Items (50+ order items)**
- Detailed line items for retailer orders
- Product names, variants, quantities, and pricing
- Realistic line totals

### 13. **Messages (40+ messages)**
- Communication between all user types
- Various message types (order_inquiry, delivery_update, quality_feedback, etc.)
- Different priorities and read statuses
- Optional attachments

### 14. **Invoices (25+ invoices)**
- Invoices for both farmer and retailer orders
- Realistic invoice numbers, amounts, and due dates
- Various payment statuses

### 15. **Payments (30+ payments)**
- Payment records for invoices
- Different payment methods (bank_transfer, mobile_money, cash, etc.)
- Various payment statuses

## Business Logic & Relationships

### **Supply Chain Flow**
1. **Farmers** harvest coffee and create inventory
2. **Processors** place orders with farmers for raw materials
3. **Processors** convert raw materials into finished products using recipes
4. **Retailers** place orders with processors for finished products
5. **Retailers** sell products to end customers

### **Logical Connections**
- Farmer harvests → Processor raw material inventory
- Product recipes → Production planning
- Employee assignments → Order processing
- Message communication → Supply chain coordination
- Invoice/payment tracking → Financial management

### **Realistic Data Patterns**
- Coffee varieties and processing methods match industry standards
- Pricing follows realistic market rates
- Quantities reflect actual business volumes
- Dates and schedules are logically connected
- Status transitions follow business workflows

## Usage

### Running the Seeder
```bash
php artisan db:seed --class=ComprehensiveCoffeeChainSeeder
```

### Running All Seeders
```bash
php artisan db:seed
```

### Fresh Database with Seeding
```bash
php artisan migrate:fresh --seed
```

## Data Quality Features

### **Consistency**
- All foreign key relationships are properly maintained
- Data types match model definitions
- Required fields are always populated

### **Realism**
- Company names reflect actual business types
- Email addresses follow logical patterns
- Phone numbers use Kenyan format
- Addresses are realistic Kenyan locations

### **Completeness**
- All major business entities are represented
- All user types have corresponding data
- All product categories are covered
- All business processes are simulated

## Customization

The seeder can be easily customized by:
- Modifying the data arrays in each method
- Adding new data generation logic
- Adjusting quantities and ranges
- Adding new business entities

## Dependencies

- Laravel Framework
- Carbon for date handling
- Faker for random data generation (optional)
- Database migrations must be run first

## Notes

- The seeder clears existing data before inserting new data
- All timestamps are set to realistic values
- Foreign key constraints are properly handled
- The seeder is designed to be run multiple times safely 