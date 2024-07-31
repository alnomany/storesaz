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
        Schema::table('suppliers', function (Blueprint $table) {
            //
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('accont_number')->nullable();
            $table->string('accont_name')->nullable();
            $table->string('dissacount')->nullable();
            $table->string('commercial_record')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('code')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            //
        });
    }
};
