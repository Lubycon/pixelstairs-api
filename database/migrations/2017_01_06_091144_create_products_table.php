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
            $table->string('market_product_id',200);
            $table->string('haitao_product_id',200)->nullable();

            $table->integer('category_id')->nullable();
            $table->integer('division_id')->nullable();
            $table->integer('sector_group_id')->nullable();

            $table->string('market_id','4');

            $table->integer('brand_id');
            $table->integer('seller_id');
            $table->integer('gender_id');
            $table->integer('manufacturer');

            $table->integer('translate_name_id');
            $table->integer('translate_description_id');

            $table->integer('original_price');
            $table->integer('lower_price');
            $table->string('price_unit',10);
            $table->integer('domestic_delivery_price');
            $table->boolean('is_free_delivery');

            $table->double('weight',4,2)->nullable();
            $table->integer('stock');
            $table->integer('safe_stock');

            $table->longtext('thumbnail_url');
            $table->longtext('url');

            $table->string('status_code')->default('0300');

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
