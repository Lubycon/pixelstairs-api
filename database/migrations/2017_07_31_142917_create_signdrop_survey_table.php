<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSigndropSurveyTable extends Migration {

    public function up()
    {
        Schema::create('signdrop_surveys', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('signdrop_id');
            $table->integer('signdrop_answer_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('signdrop_surveys');
    }
}