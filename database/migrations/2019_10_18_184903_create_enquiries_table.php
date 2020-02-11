<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEnquiriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('enquiries', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('subject', 200);
			$table->text('message', 65535);
			$table->string('mobile', 15);
			$table->timestamps();
			$table->integer('user_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('enquiries');
	}

}
