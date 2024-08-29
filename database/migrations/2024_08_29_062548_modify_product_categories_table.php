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
        //
        Schema::table('product_categories', function (Blueprint $table) {
            // Adding a new string column with a default empty string
            $table->string('type')->default('');
            
            // Adding a new integer column with a default value of 0
            $table->integer('chart_account_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        
    }
};
