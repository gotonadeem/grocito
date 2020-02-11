<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->bigInteger('user_id');
			$table->integer('warehouse_id');
			$table->integer('seller_id');
			$table->float('total_amount', 10, 0);
			$table->float('admin_commission', 10, 0);
			$table->bigInteger('address_id');
			$table->string('order_id', 50);
			$table->string('ord_payment_id');
			$table->integer('delivery_boy_id');
			$table->text('reason', 65535);
			$table->string('shipped_by', 100);
			$table->string('dock_no', 50);
			$table->float('payment_amount', 10, 0);
			$table->float('gst_amount', 10, 0);
			$table->float('shipping_charge', 10, 0);
			$table->float('cashback_amount', 10, 0);
			$table->float('extra_amount', 10, 0);
			$table->float('wallet_amount', 10, 0);
			$table->float('net_amount', 10, 0);
			$table->float('sgst_amount', 10, 0);
			$table->boolean('wallet_use')->default(0);
			$table->float('wallet_pay', 10, 0);
			$table->string('payment_mode', 10);
			$table->enum('payment_status', array('success','faild','cod'))->default('faild');
			$table->text('status_message');
			$table->string('transaction_id', 50);
			$table->string('delivery_type', 50);
			$table->string('delivery_date', 100);
			$table->string('delivery_time', 100);
			$table->string('express_time', 100)->nullable();
			$table->date('shipped_date');
			$table->enum('status', array('pending','incomplete','cancelled','return','exchange','assign_to_rider','delivered','ready_to_shiped','assign_to_warehouse','assign_to_rider_to_deliverd'));
			$table->timestamps();
			$table->boolean('is_cod_submitted')->default(0);
			$table->float('distance', 10, 0);
			$table->float('d_p_d_amount', 10, 0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders');
	}

}
