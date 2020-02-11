<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCartsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carts', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->integer('seller_id');
			$table->integer('product_id');
			$table->text('attributes', 65535);
			$table->integer('is_return');
			$table->boolean('is_exchange')->default(0);
			$table->integer('item_id');
			$table->integer('qty');
			$table->float('price', 10, 0);
			$table->float('sprice', 10, 0);
			$table->float('admin_commission', 10, 0);
			$table->float('gst_percentage', 10, 0)->default(0);
			$table->string('weight');
			$table->string('size', 20);
			$table->string('product_image', 200);
			$table->string('product_name', 200);
			$table->float('shipping_free_amount', 10, 0);
			$table->string('system_address', 100);
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
		Schema::drop('carts');
	}

}
