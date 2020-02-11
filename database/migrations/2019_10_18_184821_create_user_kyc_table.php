<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserKycTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_kyc', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->index('user_id');
			$table->integer('state_id');
			$table->integer('country_id');
			$table->integer('city_id');
			$table->string('f_name', 50);
			$table->string('l_name', 50);
			$table->string('dob', 100);
			$table->text('address', 65535);
			$table->string('pincode', 20);
			$table->string('delivery_pincode', 250);
			$table->string('gender', 10);
			$table->string('cin_number', 50);
			$table->string('cin_image', 100);
			$table->string('aadhar_number', 20)->nullable();
			$table->string('tan_number', 30);
			$table->string('aadhar_image', 100)->nullable();
			$table->string('pan_number', 20)->nullable();
			$table->string('gst_number', 50);
			$table->string('pan_image', 100)->nullable();
			$table->string('seller_image', 200);
			$table->string('cancel_cheque', 200);
			$table->string('account_number', 50);
			$table->string('bank_name');
			$table->string('ifsc_code', 20);
			$table->string('account_holder_name', 200);
			$table->string('alternate_mobile_no', 15);
			$table->string('food_license_no', 100);
			$table->string('business_reg_no', 100);
			$table->string('address_1', 200);
			$table->string('address_2', 200);
			$table->timestamps();
			$table->boolean('is_delete');
			$table->string('profile_image', 50);
			$table->string('driving_licence_image');
			$table->integer('tc')->default(1);
			$table->string('signature', 100);
			$table->float('cartlay_commission', 10, 0);
			$table->float('payment_plan', 10, 0);
			$table->string('business_name', 100);
			$table->string('business_address', 100);
			$table->integer('warehouse_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_kyc');
	}

}
