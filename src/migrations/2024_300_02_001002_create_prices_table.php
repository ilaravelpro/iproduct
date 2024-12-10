<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 4/3/20, 7:49 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('creator_id')->nullable()->unsigned();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->bigInteger('price_first')->nullable();
            $table->bigInteger('price_sale')->nullable();
            $table->bigInteger('price_cost')->nullable();
            $table->bigInteger('price_benefit')->nullable();
            $table->bigInteger('price_tax')->nullable();
            $table->bigInteger('price_pay')->nullable();
            $table->bigInteger('stock')->nullable();
            $table->bigInteger('sales')->nullable();
            $table->bigInteger('presales')->nullable();
            $table->bigInteger('discount_type')->nullable();
            $table->bigInteger('discount_value')->nullable();
            $table->boolean('is_tax')->nullable();
            $table->boolean('is_discount_benefit')->nullable();
            $table->string('currency')->nullable()->default('IRT');
            $table->timestamp('discount_start_at')->nullable();
            $table->timestamp('discount_end_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
};
