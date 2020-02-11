<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryBoyNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_boy_notifications', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('order_id');
			$table->string('distance', 20);
			$table->integer('vendor_id');
			$table->integer('warehouse_id');
			$table->integer('delivery_boy_id');
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
		Schema::drop('delivery_boy_notifications');
	}

}
