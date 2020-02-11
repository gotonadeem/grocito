<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryBoyCommissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_boy_commissions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->float('base_income', 10, 0);
			$table->float('per_km', 10, 0);
			$table->float('bonus', 10, 0);
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
		Schema::drop('delivery_boy_commissions');
	}

}
