<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_notes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('product_id');
			$table->enum('heading', array('product_blocked','product_not_verified_yet','comment'));
			$table->text('message', 65535);
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
		Schema::drop('product_notes');
	}

}
