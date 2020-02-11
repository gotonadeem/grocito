<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSellerPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seller_payments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->float('amount', 10, 0);
			$table->float('commission', 10, 0);
			$table->float('tcs_amount', 10, 0)->nullable();
			$table->date('order_date');
			$table->string('transaction_id', 30);
			$table->enum('payment_type', array('daily','settlement'));
			$table->enum('type', array('withdraw','deposit'));
			$table->integer('sender_id');
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
		Schema::drop('seller_payments');
	}

}
