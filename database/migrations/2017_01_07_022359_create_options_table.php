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
            $table->integer('sku_id');
            $table->string('original_name',300);
            $table->string('chinese_name',300);
            $table->string('korean_name',300)->nullable();
            $table->string('english_name',300)->nullable();
            $table->integer('price');
            $table->integer('stock')->nullable();
            $table->integer('safe_stock')->nullable();
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
        Schema::drop('options');
    }
}
