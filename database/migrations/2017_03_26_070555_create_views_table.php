<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateViewsTable extends Migration {

	public function up()
	{
		Schema::create('views', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('content_id')->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('views');
	}
}