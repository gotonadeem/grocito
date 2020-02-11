<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCouponsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupons', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 40);
			$table->date('start_date');
			$table->date('end_date');
			$table->string('discount_amount', 10);
			$table->string('discount_unit', 11);
			$table->string('no_of_usage', 10);
			$table->string('usage_per_user', 10);
			$table->enum('status', array('0','1'))->default('1');
			$table->float('min_ord_amount', 10, 0);
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
		Schema::drop('coupons');
	}

}
