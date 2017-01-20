<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_key_id_0');
            $table->integer('option_key_id_1');
            $table->integer('option_key_id_2');
            $table->integer('option_key_id_3');
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
        Schema::drop('option_collections');
    }
}
