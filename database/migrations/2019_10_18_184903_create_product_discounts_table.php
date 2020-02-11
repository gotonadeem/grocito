<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductDiscountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_discounts', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('product_id');
			$table->float('discount_amount', 10, 0);
			$table->enum('discount_unit', array('fixed','percent'));
			$table->date('from_date');
			$table->date('to_date');
			$table->timestamps();
			$table->boolean('status')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_discounts');
	}

}
