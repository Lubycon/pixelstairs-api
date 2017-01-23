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
            $table->integer('product_id');
            $table->string('sku',100);
            $table->integer('translate_name_id');
            $table->integer('price');
            $table->integer('stock');
            $table->integer('safe_stock');
            $table->integer('option_collection_id');
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
