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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            //suppliers
            $table->bigInteger('supplier_id');

            $table->string('country')->nullable();
            $table->string('city')->nullable();

            $table->string('billing_no')->nullable();
            $table->string('notes')->nullable();
            //products
            $table->bigInteger('product_id');

            $table->string('discount')->nullable();
            $table->string('the_amount_paid')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('the_amount_owed_supplier')->nullable();
            $table->string('due_date_payment')->nullable();
            $table->string('date')->nullable();






            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
