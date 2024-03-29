<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePasswordResetsTable extends Migration {

	public function up()
	{
		Schema::create('password_resets', function(Blueprint $table) {
			$table->increments('id');
			$table->string('email', 255);
			$table->string('token', 255);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('password_resets');
	}
}