<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWithdrawalWalletTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('withdrawal_wallet', function(Blueprint $table)
		{
			$table->integer('id')->default(0)->primary();
			$table->integer('user_id');
			$table->integer('order_id');
			$table->enum('status', array('withdraw'))->default('withdraw');
			$table->float('amount', 10, 0);
			$table->boolean('transaction_status');
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
		Schema::drop('withdrawal_wallet');
	}

}
