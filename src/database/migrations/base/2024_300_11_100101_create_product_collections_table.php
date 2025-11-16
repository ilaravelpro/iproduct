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
        Schema::smartCreate('product_collections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('creator_id')->nullable()->unsigned();
            $table->foreign('creator_id')->references('id')->on('users')->noActionOnDelete();
            $table->bigInteger('image_id')->nullable()->unsigned();
            $table->foreign('image_id')->references('id')->on('posts')->noActionOnDelete();
            $table->bigInteger('parent_id')->nullable()->unsigned();
            $table->foreign('parent_id')->references('id')->on('product_collections')->noActionOnDelete();
            $table->string('title')->nullable();
            $table->string('title_second')->nullable();
            $table->string('slug')->nullable();
            $table->string('type')->nullable()->default('product');
            $table->string('template')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->string('locale')->nullable();
            $table->string('status')->default('draft');
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
        Schema::dropIfExists('product_collections');
    }
};
