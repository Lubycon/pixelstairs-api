<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminProcessLogsTable extends Migration {

	public function up()
	{
		Schema::create('admin_process_logs', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->ipAddress('user_ip');
            $table->longText('url');
            $table->string('request_method',10);
            $table->longText('request_json')->nullable();
            $table->timestamps();
            $table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('admin_process_logs');
	}
}