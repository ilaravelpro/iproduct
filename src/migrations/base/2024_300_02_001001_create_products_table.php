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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('creator_id')->nullable()->unsigned();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('image_id')->nullable()->unsigned();
            $table->foreign('image_id')->references('id')->on('posts')->onDelete('cascade');
            $table->bigInteger('collection_id')->nullable()->unsigned();
            $table->foreign('collection_id')->references('id')->on('product_collections')->onDelete('cascade');
            $table->string('model')->nullable();
            $table->bigInteger('model_id')->nullable()->unsigned();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->bigInteger('quantity_default')->nullable();
            $table->bigInteger('quantity_min')->nullable();
            $table->bigInteger('quantity_max')->nullable();
            $table->bigInteger('sales')->default(0);
            $table->bigInteger('presales')->default(0);
            $table->double('avg_rates')->default(0);
            $table->bigInteger('count')->nullable();
            $table->integer('year_production')->nullable();
            $table->string('type')->nullable();
            $table->string('template')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('is_bulk')->nullable();
            $table->boolean('is_virtual')->default(0);
            $table->boolean('is_stackable')->default(0);
            $table->integer('is_tax')->default(0);
            $table->integer('is_production')->default(0);
            $table->integer('is_produced')->default(0);
            $table->integer('is_buyout')->default(0);
            $table->integer('order')->default(0);
            $table->string('local')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('first_produced_at')->nullable();
            $table->timestamp('last_produced_at')->nullable();
            $table->timestamp('produced_at')->nullable();
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
        Schema::dropIfExists('products');
    }
};
