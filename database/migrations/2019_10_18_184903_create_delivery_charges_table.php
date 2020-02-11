<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryChargesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_charges', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('city_id');
			$table->enum('type', array('standard','express'));
			$table->string('radius', 20);
			$table->float('radius_charge', 10, 0);
			$table->float('out_of_radius_charge', 10, 0);
			$table->float('min_order', 10, 0);
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
		Schema::drop('delivery_charges');
	}

}
