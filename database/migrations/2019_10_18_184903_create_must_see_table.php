<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMustSeeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('must_see', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->enum('language', array('eng','hindi'))->default('eng');
			$table->string('title', 200);
			$table->string('link', 200);
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
		Schema::drop('must_see');
	}

}
