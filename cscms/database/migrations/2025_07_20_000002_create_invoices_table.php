<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->date('date');
            $table->string('customer');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}; 