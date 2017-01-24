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

            $table->integer('category_id')->nullable()->unsigned();
            $table->integer('division_id')->nullable()->unsigned();
            $table->integer('section_group_id')->nullable()->unsigned();

            $table->string('market_id','4');

            $table->integer('brand_id')->unsigned();
            $table->integer('seller_id')->unsigned();
            $table->integer('gender_id')->unsigned();
            $table->integer('manufacturer_id')->unsigned();

            $table->integer('translate_name_id')->unsigned();
            $table->integer('translate_description_id')->unsigned();

            $table->integer('original_price')->unsigned();
            $table->integer('lower_price')->unsigned();
            $table->integer('unit_id')->unsigned();
            $table->integer('domestic_delivery_price')->unsigned();
            $table->boolean('is_free_delivery')->unsigned();

            $table->double('weight',8,2)->nullable();
            $table->integer('stock')->unsigned();
            $table->integer('safe_stock')->unsigned();

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
