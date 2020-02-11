<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderTrackingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_trackings', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('order_id');
			$table->text('reason', 65535);
			$table->dateTime('date');
			$table->enum('type', array('pending','incomplete','cancelled','return','exchange','assign_to_rider','delivered','ready_to_shiped','assign_to_warehouse','assign_to_rider_to_deliverd'));
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
		Schema::drop('order_trackings');
	}

}
