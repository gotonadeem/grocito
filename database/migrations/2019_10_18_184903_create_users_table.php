<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('reff_code', 50);
			$table->string('ref_by', 100);
			$table->string('username', 50);
			$table->string('unique_code', 50);
			$table->string('agent_id', 20);
			$table->integer('category_id');
			$table->string('email', 100);
			$table->string('password');
			$table->string('simple_pass');
			$table->integer('otp');
			$table->string('mobile', 15);
			$table->integer('merchant_count');
			$table->string('is_email_varifried', 111);
			$table->boolean('is_otp_varified');
			$table->integer('role_id')->default(3)->index('role_id')->comment('3=>Customer,2=>seller,4=>Delivery Boy,5=>agent');
			$table->boolean('activated')->default(1);
			$table->boolean('is_available')->default(1);
			$table->boolean('banned')->default(0);
			$table->enum('verify_status', array('requested','kyc_completed','verified'))->default('requested');
			$table->string('ban_reason')->nullable();
			$table->string('new_password_key', 50)->nullable();
			$table->string('reset_password_token', 111)->nullable();
			$table->string('new_email', 100)->nullable();
			$table->string('new_email_key', 50)->nullable();
			$table->string('remember_token', 111)->nullable();
			$table->string('device_token', 200);
			$table->string('device_type', 200);
			$table->string('ip_address', 111);
			$table->string('transaction_id', 100);
			$table->string('order_id', 20);
			$table->timestamp('last_login')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('rider_lat', 150);
			$table->string('rider_long', 150);
			$table->string('last_ip');
			$table->timestamps();
			$table->timestamp('deleted')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('new_password_requested');
			$table->boolean('is_active')->nullable()->default(0);
			$table->string('login_time', 10);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
