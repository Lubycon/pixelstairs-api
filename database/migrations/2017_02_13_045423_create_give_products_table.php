<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiveProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('give_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('review_id');
            $table->integer('apply_user_id');
            $table->integer('accept_user_id');
            $table->string('give_status_code',4)->default('0400');
            $table->integer('award_id');
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
        Schema::drop('give_products');
    }
}
