<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderRmaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_rma', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('order_meta_id');
			$table->integer('order_id');
			$table->integer('product_id');
			$table->text('reason', 65535);
			$table->text('comment', 65535);
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
		Schema::drop('order_rma');
	}

}
