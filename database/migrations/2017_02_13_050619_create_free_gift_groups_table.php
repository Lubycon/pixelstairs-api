<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreeGiftGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_gift_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('stock_per_each');
            $table->integer('first_deploy_count');
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
        Schema::drop('free_gift_groups');
    }
}
