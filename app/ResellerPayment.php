<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class ResellerPayment extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'order_id',
        'seller_id',
        'order_meta_id',
        'amount',
        'extra_amount',
        'shipping_charge',
        'return_amount',
        'type',
		'exchange_amount',
    ];
    protected $table="reseller_payments";
	
	public function order()
    {
        return $this->belongsTo('App\Order','order_id');
    }
}
