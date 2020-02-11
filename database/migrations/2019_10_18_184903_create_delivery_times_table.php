<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryTimesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_times', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('city_id');
			$table->string('time_interval', 100)->default('1');
			$table->string('express_time')->default('45');
			$table->enum('type', array('standard','express'));
			$table->string('start_time', 50)->default('10:00AM');
			$table->string('end_time', 50)->default('08:00PM');
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
		Schema::drop('delivery_times');
	}

}
