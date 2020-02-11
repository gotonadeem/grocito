<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReferralSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('referral_settings', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->float('referrer_amount', 10, 0);
			$table->float('referral_amount', 10, 0);
			$table->text('referral_description', 16777215);
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
		Schema::drop('referral_settings');
	}

}
