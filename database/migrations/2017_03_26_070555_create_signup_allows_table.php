<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSignupAllowsTable extends Migration {

	public function up()
	{
		Schema::create('signup_allows', function(Blueprint $table) {
			$table->increments('id');
			$table->string('email', 255);
			$table->string('token', 255);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('signup_allows');
	}
}