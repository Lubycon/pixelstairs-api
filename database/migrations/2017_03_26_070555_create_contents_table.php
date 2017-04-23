<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentsTable extends Migration {

	public function up()
	{
		Schema::create('contents', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('license_code', 4);
//            $table->integer('thumbnail_image_id')->unsigned();
			$table->integer('image_group_id')->unsigned();
			$table->string('title', 255);
			$table->text('description')->nullable();
			$table->integer('view_count')->unsigned()->default('0');
			$table->integer('like_count')->unsigned()->default('0');
			$table->string('hash_tags', 1000)->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('contents');
	}
}