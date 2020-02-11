<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_addresses', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->enum('type', array('home','office','other'));
			$table->string('name', 100);
			$table->integer('user_id');
			$table->string('mobile', 15);
			$table->text('address', 65535);
			$table->string('house', 50);
			$table->string('street', 100);
			$table->string('city', 20);
			$table->string('landmark', 100);
			$table->string('state', 20);
			$table->string('pincode', 15);
			$table->string('lattitude', 50);
			$table->string('longitude', 50);
			$table->boolean('is_default')->default(0);
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
		Schema::drop('user_addresses');
	}

}
