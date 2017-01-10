<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('market_id',4);
            $table->integer('category_id');
            $table->string('name',30);
            $table->string('data_number',20);
            $table->boolean('is_active');
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
        Schema::drop('market_divisions');
    }
}
