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
            $table->string('email')->unique();
            $table->string('nickname')->unique();
            $table->string('password', 60);

            $table->enum('grade',['admin','user'])->default('user');
            $table->enum('status', ['active','inactive','drop'])->default('inactive');

            $table->boolean('newsletter')->default(false);
            $table->boolean('terms_of_service')->default(true);
            $table->boolean('private_policy')->default(true);

            $table->integer('occupation_id')->unsigned()->nullable();
            $table->integer('country_id')->unsigned();

            $table->string('profile_img')->nullable();
            $table->string('description',255)->nullbable();
            $table->string('company',255)->nullable();
            $table->string('city',255)->nullable();
            $table->string('mobile',255)->nullable();
            $table->string('fax',255)->nullable();
            $table->string('web')->nullable();

            $table->enum('email_public',['Public','Private'])->default('Public');
            $table->enum('mobile_public',['Public','Private'])->default('Public');
            $table->enum('fax_public',['Public','Private'])->default('Public');
            $table->enum('web_public',['Public','Private'])->default('Public');

            $table->integer('sns_id')->nullable();
            $table->enum('sns_code', ['0100','0101','0102']);
            $table->string('sns_token',100)->nullbable();

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
