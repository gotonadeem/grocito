<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model
{
	protected $table="seller_payments";	
    protected $fillable = [
        'user_id',
        'type',
		'amount',
		'tcs_amount',
        'order_date',
        'sender_id',
        'week_number',
        'from_date',
        'seller_commission',
        'commission',
        'to_date',
        'transaction_id',
        'payment_type',
        'created_at',
		'order_id',
    ];
 
  public function seller_data()
    {
        return $this->belongsTo('App\User','user_id');
    }
  public function order_meta_data()
  {
	 return $this->belongsTo('App\OrderMeta','user_id','seller_id'); 
  }
  public function order()
  {
	 return $this->belongsTo('App\order','order_id'); 
  }
  
  
}