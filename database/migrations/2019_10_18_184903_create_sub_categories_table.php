<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sub_categories', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('category_id');
			$table->string('name', 100);
			$table->string('category_slug', 100);
			$table->string('slug', 100);
			$table->text('description', 65535);
			$table->string('image', 200);
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
		Schema::drop('sub_categories');
	}

}
