<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWalletsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wallets', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->integer('ref_id');
			$table->string('merchant_id', 12);
			$table->float('amount', 10, 0);
			$table->enum('payment_type', array('placed_order','return_order','refer_and_earn','add_balance','cashback','first_order_cashback'));
			$table->string('status', 100);
			$table->enum('type', array('withdraw','deposit'));
			$table->boolean('commission_status');
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
		Schema::drop('wallets');
	}

}
