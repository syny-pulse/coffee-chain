<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->enum('transaction_type', ['stock_in', 'stock_out', 'adjustment', 'transfer', 'production', 'waste']);
            $table->enum('inventory_type', ['raw_material', 'finished_goods']);
            $table->unsignedBigInteger('inventory_item_id');
            $table->decimal('quantity_change', 10, 2);
            $table->decimal('unit_cost', 8, 2)->nullable();
            $table->enum('reference_type', ['farmer_order', 'retailer_order', 'production', 'adjustment', 'waste'])->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('transaction_date')->useCurrent();

            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('set null');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_transactions');
    }
};