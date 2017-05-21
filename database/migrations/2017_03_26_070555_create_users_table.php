<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->string('email', 255);
			$table->string('password', 1000);
			$table->string('nickname', 20);
            $table->enum('gender', array('male', 'female', 'etc'));
            $table->timestamp('birthday');
			$table->enum('grade', array('super_admin', 'admin', 'general'))->default('general');
			$table->enum('status', array('active', 'inactive', 'drop'));
			$table->integer('image_id')->unsigned()->nullable();
            $table->string('token', 40)->nullable();
			$table->timestamp('last_login_time')->nullable();
            $table->boolean('newsletters_accepted')->default(false);
            $table->boolean('terms_of_service_accepted');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}