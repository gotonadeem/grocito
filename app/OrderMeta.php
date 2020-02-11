<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class OrderMeta extends Authenticatable
{
    protected $fillable = [
        'order_id','product_id','sub_order_id','attributes','net_amount','parent_id','is_return','product_image','price','product_commission','product_name','shipping_free_amount','cancel_request','seller_id','image','item_id','is_exchange','size','qty','size','item_id','status','expected_delivery_date','weight','message','return_status','exchange_status',
    ];
	function order()
	{
		 return $this->belongsTo('App\Order','order_id');
	}
	function seller()
	{
		 return $this->belongsTo('App\User','seller_id','id');
	}
	function seller_kyc()
	{
		 return $this->belongsTo('App\UserKyc','seller_id','user_id');
	}
	function reseller_payment()
	{
		 return $this->belongsTo('App\ResellerPayment','order_id','order_id');
	}
	function exchange_order()
	{
		 return $this->belongsTo('App\OrderExchange','id','order_meta_id');
	}
	function return_order()
	{
		 return $this->belongsTo('App\OrderRmaDetail','id','order_meta_id');
	}
	
	function product()
	{
		 return $this->belongsTo('App\Product','product_id');
	}
	function product_image()
	{
		 return $this->belongsTo('App\Product','product_id');
	}
}
