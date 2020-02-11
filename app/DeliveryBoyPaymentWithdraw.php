<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class DeliveryBoyPaymentWithdraw extends Model
{
	protected $table="delivery_boy_withdraw_payments";	
    protected $fillable = [
        'user_id',
		'reason',
		'amount',
		'date',
    ];
}