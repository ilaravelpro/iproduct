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
        Schema::create('products_attachments', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('attachment_id')->unsigned();
            $table->string('type')->nullable();
            $table->primary(['product_id' , 'attachment_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_attachments');
    }
};
