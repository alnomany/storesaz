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
        Schema::table('purchases', function (Blueprint $table) {
            //
            $table->string('purchase_id')->default('0');
            $table->integer('warehouse_id');
            $table->integer('purchase_number')->default('0');
            $table->integer('status')->default('0');
            $table->integer('category_id');
            $table->integer('created_by')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
};
