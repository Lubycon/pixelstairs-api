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
            $table->string('haitao_product_id',200)->nullable();

            $table->integer('category_id');
            $table->integer('division_id');
            $table->integer('sector_id_0');
            $table->integer('sector_id_1')->nullable();
            $table->integer('sector_id_2')->nullable();

            $table->string('market_id','4');

            $table->integer('brand_id')->nullable();

            $table->string('original_title');
            $table->string('chinese_title');
            $table->string('korean_title')->nullable();
            $table->string('english_title')->nullable();

            $table->string('original_description')->nullable();
            $table->string('chinese_description')->nullable();
            $table->string('korean_description')->nullable();
            $table->string('english_description')->nullable();

            $table->integer('price');
            $table->integer('domestic_delivery_price');
            $table->boolean('is_free_delivery');

            $table->integer('stock');
            $table->integer('safe_stock');

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
