<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderMetasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_metas', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('parent_id')->default(0);
			$table->integer('seller_id');
			$table->integer('order_id')->index('order_id');
			$table->string('sub_order_id', 111);
			$table->integer('product_id');
			$table->integer('item_id');
			$table->string('weight');
			$table->string('size', 20);
			$table->float('price', 10, 0);
			$table->float('offer_amount', 10, 0);
			$table->float('product_commission', 10, 0);
			$table->float('gst_amount', 10, 0);
			$table->float('cashback_amount', 10, 0);
			$table->integer('qty');
			$table->text('attributes', 65535);
			$table->string('product_image', 200);
			$table->string('product_name', 200);
			$table->float('shipping_free_amount', 10, 0);
			$table->integer('is_return');
			$table->boolean('is_exchange');
			$table->date('expected_delivery_date');
			$table->boolean('cancel_request')->default(0);
			$table->text('message', 65535);
			$table->timestamps();
			$table->enum('status', array('pending','cancelled','assign_to_rider','delivered','incomplete','exchange','return','ready_to_shiped','assign_to_warehouse','assign_to_rider_to_deliverd'))->default('incomplete');
			$table->boolean('return_status')->default(0);
			$table->boolean('exchange_status')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_metas');
	}

}
