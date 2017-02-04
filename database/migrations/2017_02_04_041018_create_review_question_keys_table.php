<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewQuestionKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_question_keys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('division_id')->unsigned()->nullable();
            $table->integer('translate_description_id')->unsigned();
            $table->boolean('is_common')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('review_question_keys');
    }
}
