<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_notes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->enum('heading', array('order_cancelled','comment','order_pending'));
			$table->text('message', 65535);
			$table->timestamps();
			$table->integer('order_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_notes');
	}

}
