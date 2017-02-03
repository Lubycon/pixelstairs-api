<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslateDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translate_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('original',500);
            $table->string('chinese',500);
            $table->string('korean',500)->nullable();
            $table->string('english',500)->nullable();
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
        Schema::drop('translate_descriptions');
    }
}
