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
        Schema::table('farmer_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('processor_company_id')->nullable()->after('farmer_company_id');
            $table->foreign('processor_company_id')->references('company_id')->on('companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmer_orders', function (Blueprint $table) {
            $table->dropForeign(['processor_company_id']);
            $table->dropColumn('processor_company_id');
        });
    }
};
