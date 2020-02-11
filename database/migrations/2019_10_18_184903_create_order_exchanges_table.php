<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderExchangesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_exchanges', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('order_id');
			$table->integer('order_meta_id');
			$table->integer('order_rma_id');
			$table->integer('product_id');
			$table->string('size', 100);
			$table->string('image', 100);
			$table->enum('status', array('pending','completed','unapproved'))->default('pending');
			$table->boolean('is_approved')->comment('0=>pending, 1=>approved, 2=>unApproved	');
			$table->text('message', 65535);
			$table->string('dock_no', 50);
			$table->timestamps();
			$table->date('approved_date');
			$table->integer('address_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_exchanges');
	}

}
