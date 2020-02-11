<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderReturnVideosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_return_videos', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('order_meta_id');
			$table->integer('order_id');
			$table->integer('product_id');
			$table->string('video_name', 200);
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
		Schema::drop('order_return_videos');
	}

}
