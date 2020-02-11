<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWarehousesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('warehouses', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('city_id');
			$table->string('name', 100);
			$table->string('address', 200);
			$table->string('lattitude', 20);
			$table->string('longitude', 20);
			$table->integer('warehouse_pincode');
			$table->string('pincode');
			$table->string('subadmin_id', 100);
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
		Schema::drop('warehouses');
	}

}
