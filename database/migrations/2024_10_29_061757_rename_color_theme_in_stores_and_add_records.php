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
        Schema::table('stores', function (Blueprint $table) {
            //
            $table->string('color_theme2')->nullable()->after('store_theme');
            $table->string('top_head_text')->nullable()->after('color_theme2');

            $table->renameColumn('color_theme', 'color_theme1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            //
        });
    }
};
