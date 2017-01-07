<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id',200);
            $table->string('haitao_product_id',200);

            $table->integer('category_id');
            $table->integer('division_id');
            $table->integer('sector_id');

            $table->string('market_id','4');
            $table->integer('brand_id')->nullable();

            $table->string('original_title');
            $table->string('chinese_title');
            $table->string('korean_title')->nullable();
            $table->string('english_title')->nullable();

            $table->string('description')->nullable();

            $table->integer('price');
            $table->integer('domestic_delivery_price');
            $table->boolean('is_free_delivery');

            $table->longtext('url');

            $table->string('status_code');

            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();

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
        Schema::drop('products');
    }
}
