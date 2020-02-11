<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSocialSettingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('social_setting', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('fb_app_id');
			$table->string('fb_app_secerate');
			$table->string('gmail_app_id');
			$table->string('gmail_app_secerate');
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
		Schema::drop('social_setting');
	}

}
