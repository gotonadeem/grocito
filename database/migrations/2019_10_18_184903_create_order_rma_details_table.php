<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderRmaDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_rma_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('order_rma_id');
			$table->integer('order_meta_id');
			$table->integer('order_id');
			$table->float('shipping_charge', 10, 0);
			$table->integer('product_id');
			$table->string('account_number', 50);
			$table->string('account_holder_name', 100);
			$table->string('ifsc_code', 20);
			$table->integer('address_id');
			$table->text('status', 65535);
			$table->boolean('is_approved')->comment('0=>pending, 1=>approved, 2=>unApproved');
			$table->string('dock_no', 50);
			$table->timestamps();
			$table->date('approved_date');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_rma_details');
	}

}
