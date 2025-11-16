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
        Schema::smartCreate('product_editions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('creator_id')->nullable()->unsigned();
            $table->foreign('creator_id')->references('id')->on('users')->noActionOnDelete();
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->noActionOnDelete();
            $table->bigInteger('article_id')->nullable()->unsigned();
            $table->foreign('article_id')->references('id')->on('posts')->noActionOnDelete();
            $table->string('title')->nullable();
            $table->bigInteger('count')->nullable();
            $table->bigInteger('price_first')->nullable();
            $table->bigInteger('price_sale')->nullable();
            $table->timestamp('edition_at')->nullable();
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
        Schema::dropIfExists('product_editions');
    }
};
