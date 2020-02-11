<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFaqTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faq', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('section_id');
			$table->string('title');
			$table->text('description', 16777215);
			$table->integer('banned');
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
		Schema::drop('faq');
	}

}
