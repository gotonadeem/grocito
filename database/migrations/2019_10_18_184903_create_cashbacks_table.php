<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCashbacksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cashbacks', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('min_order_value');
			$table->string('cashback_per');
			$table->string('upto_cashback');
			$table->string('welcome_min_order_value');
			$table->string('welcome_cashback_per');
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
		Schema::drop('cashbacks');
	}

}
