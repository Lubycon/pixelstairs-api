<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('haitao_order_id')->unsigned();
            $table->string('haitao_user_id',50);
            $table->integer('product_id')->unsigned();
            $table->string('sku',100);
            $table->integer('quantity')->unsigned();
            $table->string('order_status_code',4);
            $table->timestamp('order_date')->nullable();
            $table->timestamp('cancel_date')->nullable();
            $table->softDeletes();
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
        Schema::drop('orders');
    }
}
