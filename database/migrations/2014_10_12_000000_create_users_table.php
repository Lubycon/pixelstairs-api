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
            $table->string('email',100)->unique();
            $table->string('name',20)->unique();
            $table->string('nickname',20)->unique();
            $table->string('password', 60);

            $table->enum('grade',['superAdmin','admin','normal'])->default('normal');
            $table->string('position',30);

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
