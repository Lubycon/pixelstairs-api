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
            $table->string('phone',30)->unique()->nullable();
            $table->string('name',20)->unique()->nullable();
            $table->string('nickname',20)->unique()->nullable();
            $table->string('password', 60);
//
            $table->string('status',10)->default('inactive');
            $table->enum('grade',['superAdmin','admin','normal'])->default('normal');
            $table->string('position',30)->nullable();

            $table->integer('gender_id')->unsigned()->nullable();
            $table->timestamp('birthday')->nullable();

            $table->integer('country_id')->nullable();
            $table->string('city',20)->nullable();
            $table->string('address1',30)->nullable();
            $table->string('address2',30)->nullable();
            $table->integer('post_code')->nullable();

            $table->longText('thumbnail_url')->nullable();

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
