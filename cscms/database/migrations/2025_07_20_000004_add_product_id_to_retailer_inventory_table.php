<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('retailer_inventory', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->after('id');
            $table->foreign('product_id')->references('product_id')->on('retailer_products')->onDelete('set null');
        });
    }
    public function down()
    {
        Schema::table('retailer_inventory', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
    }
}; 