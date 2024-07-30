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
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id');
            $table->date('date');
            $table->decimal('amount',15,2)->default('0.00');
            $table->integer('account_id');
            $table->integer('payment_method');
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->string('add_receipt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_payments');
    }
};
