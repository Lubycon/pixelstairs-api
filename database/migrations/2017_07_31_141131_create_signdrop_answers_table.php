<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSigndropAnswersTable extends Migration {

	public function up()
	{
		Schema::create('signdrop_answers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('signdrop_question_id')->nullable();
            $table->string('answer', 300);
            $table->timestamps();
            $table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('signdrop_answers');
	}
}