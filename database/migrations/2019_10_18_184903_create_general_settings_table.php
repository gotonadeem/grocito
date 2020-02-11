<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_settings', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('site_url', 111);
			$table->string('site_name');
			$table->string('site_logo', 111);
			$table->string('admin_email', 222);
			$table->string('admin_name', 100);
			$table->string('customer_support_no', 15);
			$table->string('contact_email', 222);
			$table->string('smtp_username');
			$table->string('smtp_password');
			$table->string('smtp_host');
			$table->decimal('app_version', 4, 1)->nullable();
			$table->text('special_image', 65535);
			$table->text('message', 65535);
			$table->timestamps();
			$table->float('saleplus_commission', 10, 0);
			$table->float('wallet_deduction', 10, 0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_settings');
	}

}
