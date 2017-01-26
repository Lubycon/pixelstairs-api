<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->string('sku',100);
            $table->integer('translate_name_id')->unsigned();
            $table->integer('price')->unsigned();
            $table->integer('stock')->unsigned();
            $table->integer('safe_stock')->unsigned();
            $table->integer('option_collection_id')->unsigned();
            $table->longText('thumbnail_url');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('options');
    }
}
