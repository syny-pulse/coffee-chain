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
          Schema::table('farmer_harvest', function (Blueprint $table) {
            $table->decimal('reserved_quantity_kg', 10, 2)->default(0)->after('available_quantity_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('farmer_harvest', function (Blueprint $table) {
            $table->dropColumn('reserved_quantity_kg');
        });
    }
};
