<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SyncAndMigrateProductsToRetailerProducts extends Migration
{
    public function up()
    {
        Schema::table('retailer_products', function (Blueprint $table) {
            if (!Schema::hasColumn('retailer_products', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('retailer_products', 'name')) {
                $table->string('name')->after('user_id');
            }
            if (!Schema::hasColumn('retailer_products', 'product_type')) {
                $table->string('product_type')->after('name');
            }
            if (!Schema::hasColumn('retailer_products', 'origin_country')) {
                $table->string('origin_country')->after('product_type');
            }
            if (!Schema::hasColumn('retailer_products', 'processing_method')) {
                $table->string('processing_method')->after('origin_country');
            }
            if (!Schema::hasColumn('retailer_products', 'roast_level')) {
                $table->string('roast_level')->after('processing_method');
            }
            if (!Schema::hasColumn('retailer_products', 'quantity_kg')) {
                $table->decimal('quantity_kg', 8, 2)->after('roast_level');
            }
            if (!Schema::hasColumn('retailer_products', 'price_per_kg')) {
                $table->decimal('price_per_kg', 8, 2)->after('quantity_kg');
            }
            if (!Schema::hasColumn('retailer_products', 'quality_score')) {
                $table->decimal('quality_score', 3, 1)->after('price_per_kg');
            }
            if (!Schema::hasColumn('retailer_products', 'harvest_date')) {
                $table->date('harvest_date')->after('quality_score');
            }
            if (!Schema::hasColumn('retailer_products', 'processing_date')) {
                $table->date('processing_date')->after('harvest_date');
            }
            if (!Schema::hasColumn('retailer_products', 'expiry_date')) {
                $table->date('expiry_date')->after('processing_date');
            }
            if (!Schema::hasColumn('retailer_products', 'description')) {
                $table->text('description')->nullable()->after('expiry_date');
            }
            if (!Schema::hasColumn('retailer_products', 'status')) {
                $table->string('status')->default('active')->after('description');
            }
            if (!Schema::hasColumn('retailer_products', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('retailer_products', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    public function down()
    {
        // Optionally, drop the columns if needed
    }
} 