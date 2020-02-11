<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSellerNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seller_notifications', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('seller_id');
			$table->integer('int_val')->comment('product id,order id etc');
			$table->enum('type', array('product_verify','order_placed'));
			$table->string('message');
			$table->boolean('status')->default(0);
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
		Schema::drop('seller_notifications');
	}

}
