<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePincodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pincodes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('state_id');
			$table->integer('city_id');
			$table->string('pincode', 6);
			$table->string('address', 200);
			$table->boolean('status')->default(1);
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
		Schema::drop('pincodes');
	}

}
