<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLicensesTable extends Migration {

	public function up()
	{
		Schema::create('licenses', function(Blueprint $table) {
			$table->string('code', 4);
			$table->string('description', 255);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('licenses');
	}
}