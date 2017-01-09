<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketSectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_sectors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('market_id');
            $table->integer('category_id');
            $table->integer('division_id');
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
        Schema::drop('market_sectors');
    }
}
