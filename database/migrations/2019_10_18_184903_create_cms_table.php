<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCmsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cms', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('url', 16777215);
			$table->text('name', 16777215);
			$table->text('description', 16777215);
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
		Schema::drop('cms');
	}

}
