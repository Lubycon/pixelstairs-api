<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImagesTable extends Migration {

	public function up()
	{
		Schema::create('images', function(Blueprint $table) {
			$table->increments('id');
			$table->text('url');
			$table->integer('index')->unsigned()->default('0');
			$table->boolean('is_pixel_own');
			$table->integer('image_group_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('images');
	}
}