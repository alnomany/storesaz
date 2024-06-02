<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'user_details', function (Blueprint $table){
            $table->id();
            $table->string('store_id');
            $table->string('customer_id')->nullable();
            $table->string('name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->string('billing_address');
            $table->string('billing_country');
            $table->string('billing_city');
            $table->string('billing_postalcode');
            $table->string('shipping_address')->nullable();
            $table->string('custom_field_title_1')->nullable();
            $table->string('custom_field_title_2')->nullable();
            $table->string('custom_field_title_3')->nullable();
            $table->string('custom_field_title_4')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_postalcode')->nullable();
            $table->integer('location_id')->default(0);
            $table->integer('shipping_id')->default(0);
            $table->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
