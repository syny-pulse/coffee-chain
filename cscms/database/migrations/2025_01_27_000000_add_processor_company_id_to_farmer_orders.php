<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add the processor_company_id column if it doesn't exist
        if (!Schema::hasColumn('farmer_orders', 'processor_company_id')) {
            Schema::table('farmer_orders', function (Blueprint $table) {
                $table->unsignedBigInteger('processor_company_id')->nullable()->after('order_id');
            });
        }

        // Get the first processor company ID
        $processorCompanyId = DB::table('companies')
            ->where('company_type', 'processor')
            ->value('company_id');

        // Update existing orders to have processor_company_id
        if ($processorCompanyId) {
            DB::table('farmer_orders')
                ->whereNull('processor_company_id')
                ->update(['processor_company_id' => $processorCompanyId]);
        }

        // Make the column not nullable after populating
        Schema::table('farmer_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('processor_company_id')->nullable(false)->change();
        });

        // Add foreign key constraint
        Schema::table('farmer_orders', function (Blueprint $table) {
            $table->foreign('processor_company_id')->references('company_id')->on('companies')->onDelete('cascade');
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
