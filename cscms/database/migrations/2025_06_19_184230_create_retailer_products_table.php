<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('retailer_products', function (Blueprint $table) {
            $table->id('product_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->string('product_type');
            $table->string('origin_country');
            $table->string('processing_method');
            $table->string('roast_level');
            $table->decimal('quantity_kg', 8, 2);
            $table->decimal('price_per_kg', 8, 2);
            $table->decimal('quality_score', 3, 1);
            $table->date('harvest_date');
            $table->date('processing_date');
            $table->date('expiry_date');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailer_products');
    }
}; 