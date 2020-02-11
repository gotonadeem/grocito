<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryPincodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_pincodes', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('product', 50);
			$table->string('pincode', 20);
			$table->string('city', 30);
			$table->string('state', 30);
			$table->string('region', 30);
			$table->string('prepaid', 2);
			$table->string('cod', 2);
			$table->string('reverse', 2);
			$table->string('pickup', 2);
			$table->string('serviceable_by', 20);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('delivery_pincodes');
	}

}
