<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResellerPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reseller_payments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->integer('seller_id');
			$table->integer('order_id');
			$table->integer('order_meta_id')->nullable();
			$table->float('amount', 10, 0);
			$table->float('extra_amount', 10, 0);
			$table->float('shipping_charge', 10, 0);
			$table->float('return_amount', 10, 0);
			$table->float('exchange_amount', 10, 0);
			$table->enum('type', array('order_amount','shipping_charge','margin_amount','extra_amount'))->default('margin_amount');
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
		Schema::drop('reseller_payments');
	}

}
