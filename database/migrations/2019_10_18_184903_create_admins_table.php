<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admins', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username');
			$table->string('simple_pass', 100);
			$table->string('email');
			$table->string('password');
			$table->string('remember_token');
			$table->integer('active');
			$table->integer('role')->comment('1=> admin and 2=>subadmin');
			$table->string('warehouse', 100);
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
		Schema::drop('admins');
	}

}
