<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name', 255);
            $table->enum('product_type', ['green_beans', 'roasted_beans', 'ground_coffee']);
            $table->string('origin_country', 100)->nullable();
            $table->enum('processing_method', ['washed', 'natural', 'honey'])->nullable();
            $table->enum('roast_level', ['light', 'medium', 'dark'])->nullable();
            $table->decimal('quantity_kg', 10, 2)->default(0);
            $table->decimal('price_per_kg', 8, 2)->nullable();
            $table->decimal('quality_score', 3, 1)->nullable();
            $table->date('harvest_date')->nullable();
            $table->date('processing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'reserved', 'sold', 'expired'])->default('available');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};