<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPriceFromRetailerProducts extends Migration
{
    public function up()
    {
        Schema::table('retailer_products', function (Blueprint $table) {
            if (Schema::hasColumn('retailer_products', 'price')) {
                $table->dropColumn('price');
            }
        });
    }

    public function down()
    {
        Schema::table('retailer_products', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable();
        });
    }
} 