<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSuperSubCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('super_sub_categories', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('slug', 200);
			$table->integer('category_id');
			$table->integer('sub_category_id');
			$table->string('name', 100);
			$table->text('description', 65535);
			$table->string('image', 50);
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
		Schema::drop('super_sub_categories');
	}

}
