<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTrackersTable extends Migration {

	public function up()
	{
		Schema::create('trackers', function(Blueprint $table) {
			$table->increments('id');
			$table->string('uuid', 36);
			$table->text('current_url');
			$table->text('prev_url');
			$table->integer('action')->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('trackers');
	}
}