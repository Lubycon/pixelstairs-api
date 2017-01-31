<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('haitao_user_id',100)->nullbale();

            $table->string('email',50)->unique()->nullable();
            $table->string('phone',30)->unique();
            $table->string('name',20)->unique();
            $table->string('nickname',20)->unique();
            $table->string('password', 60);
//
            $table->enum('grade',['superAdmin','admin','normal'])->default('normal');
            $table->string('position',30)->nullable();

            $table->integer('gender_id')->unsigned();
            $table->timestamp('birthday');

            $table->integer('country_id');
            $table->string('city',20);
            $table->string('address1',30);
            $table->string('address2',30);
            $table->integer('post_code');

            $table->longText('thumbnail_url');

            $table->rememberToken();
            $table->timestamp('last_login_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
