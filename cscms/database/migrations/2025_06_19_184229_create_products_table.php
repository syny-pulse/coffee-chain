<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('product_type');
            $table->string('origin_country');
            $table->string('processing_method');
            $table->string('roast_level');
            $table->decimal('quantity_kg', 8, 2); // 8 digits, 2 decimal places
            $table->decimal('price_per_kg', 8, 2);
            $table->decimal('quality_score', 3, 1); // e.g., 9.5
            $table->date('harvest_date');
            $table->date('processing_date');
            $table->date('expiry_date');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps(); // Adds created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};