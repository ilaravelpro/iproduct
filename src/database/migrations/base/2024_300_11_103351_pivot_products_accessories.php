<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::smartCreate('products_accessories', function (Blueprint $table) {
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('accessory_id')->unsigned();
            $table->primary(['product_id', 'accessory_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_accessories');
    }
};
