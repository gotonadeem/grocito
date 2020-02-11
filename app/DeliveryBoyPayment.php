<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class DeliveryBoyPayment extends Model
{
	protected $table="delivery_boy_payment";	
    protected $fillable = [
        'payment_slot_id',
        'delivery_boy_id',
		'amount',
		'distance',
        'payment_amount',
        'order_count',
        'distance_wise_amount',
        'deduction',
        'slip',
        'transaction_id',
        'description',
        'cod',
        'no_of_days',
        'base_income',
        'grocito_fee',
		'bonus',
    ];
 
	 function payment_slot()
	 {
		return $this->belongsTo('App\PaymentSlot','payment_slot_id','id'); 	 
	 }
 
}