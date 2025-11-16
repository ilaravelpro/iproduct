<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 4/3/20, 7:49 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

use iLaravel\iProduct\Database\Schema\ProductBlueprint as Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        \Schema::smartCreate('products', function (Blueprint $table) {
            $table->id();
            $table->modelId();
            $table->first();
            $table->middle();
            $table->last("draft");
            $table->timestamps();
            $table->addAlterCommands();
            $table->unique(["model_type", "slug"]);
            $table->unique(["model_type", "code"]);
        }, Blueprint::class);
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
