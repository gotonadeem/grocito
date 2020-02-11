<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRaisingComplaintsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('raising_complaints', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('complaint_id', 100);
			$table->integer('user_id');
			$table->string('title');
			$table->string('problem');
			$table->string('solution');
			$table->boolean('status');
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
		Schema::drop('raising_complaints');
	}

}
