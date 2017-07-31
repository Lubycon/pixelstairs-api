<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSigndropsTable extends Migration {

	public function up()
	{
		Schema::create('signdrops', function(Blueprint $table) {
			$table->increments('id');
            $table->integer('user_id');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('signdrops');
	}
}