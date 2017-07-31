<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSigndropQuestionsTable extends Migration {

	public function up()
	{
		Schema::create('signdrop_questions', function(Blueprint $table) {
			$table->increments('id');
			$table->string('question', 100);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('signdrop_questions');
	}
}