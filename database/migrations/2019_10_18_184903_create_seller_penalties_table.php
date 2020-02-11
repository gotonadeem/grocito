<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSellerPenaltiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seller_penalties', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('order_id');
			$table->integer('order_meta_id');
			$table->integer('seller_id');
			$table->integer('amount');
			$table->enum('type', array('return','exchange','cancelled'));
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
		Schema::drop('seller_penalties');
	}

}
