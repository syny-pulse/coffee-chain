<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropProductNameFromRetailerProducts extends Migration
{
    public function up()
    {
        Schema::table('retailer_products', function (Blueprint $table) {
            if (Schema::hasColumn('retailer_products', 'product_name')) {
                $table->dropColumn('product_name');
            }
        });
    }

    public function down()
    {
        Schema::table('retailer_products', function (Blueprint $table) {
            $table->string('product_name')->nullable();
        });
    }
} 