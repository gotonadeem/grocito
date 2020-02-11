<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->integer('brand_id');
			$table->text('slug', 65535);
			$table->string('sku', 100);
			$table->integer('category_id');
			$table->string('category_slug', 100);
			$table->integer('sub_category_id');
			$table->string('sub_category_slug', 200);
			$table->integer('super_sub_category_id');
			$table->string('super_sub_category_slug', 200);
			$table->string('name', 200)->nullable();
			$table->string('commission', 111);
			$table->float('p_gst', 10, 0)->default(0);
			$table->string('color', 155);
			$table->integer('city_id');
			$table->text('description', 65535);
			$table->string('type', 100);
			$table->timestamps();
			$table->boolean('status')->default(1);
			$table->boolean('is_admin_approved')->default(0);
			$table->boolean('stock_status')->default(1);
			$table->bigInteger('share_count');
			$table->boolean('is_cod')->default(0);
			$table->boolean('is_return')->default(0);
			$table->boolean('is_exchange');
			$table->string('related_product', 100);
			$table->boolean('is_featured');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
	}

}
