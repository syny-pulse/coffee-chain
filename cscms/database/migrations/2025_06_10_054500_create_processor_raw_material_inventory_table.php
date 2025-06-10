<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('processor_raw_material_inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->unsignedBigInteger('processor_company_id');
            $table->enum('coffee_variety', ['arabica', 'robusta']);
            $table->enum('processing_method', ['natural', 'washed', 'honey']);
            $table->enum('grade', ['grade_1', 'grade_2', 'grade_3', 'grade_4', 'grade_5']);
            $table->decimal('current_stock_kg', 10, 2)->default(0);
            $table->decimal('reserved_stock_kg', 10, 2)->default(0);
            $table->decimal('available_stock_kg', 10, 2)->default(0);
            $table->decimal('average_cost_per_kg', 10, 2)->nullable();
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('processor_company_id')->references('company_id')->on('companies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('processor_raw_material_inventory');
    }
};