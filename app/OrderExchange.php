<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class OrderExchange extends Authenticatable
{
	protected $table="order_exchanges";
    protected $fillable = [
        'order_id','order_rma_id','order_meta_id','reason','status','size','image','product_id','address_id',
		'message',
		'dock_no',
		'approved_date',
    ];
	
	function order()
	{
        return $this->belongsTo('App\Order','order_id');
	}
	
	function reseller_payment()
	{
        return $this->belongsTo('App\ResellerPayment','order_id','order_id');
	}
	
}
