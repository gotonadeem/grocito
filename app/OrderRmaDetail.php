<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class OrderRmaDetail extends Authenticatable
{
	protected $table="order_rma_details";
    protected $fillable = [
        'order_id','order_rma_id','status','dock_no','reason','order_meta_id','account_number','account_holder_name','ifsc_code','address_id','is_approved','approved_date'
    ];
	
	function order()
	{
        return $this->hasOne('App\Order','id','order_id');
	}
	
	function order_meta()
	{
        return $this->belongsTo('App\OrderMeta','order_meta_id','id');
	}
	function reseller_payment()
	{
        return $this->belongsTo('App\ResellerPayment','order_id','order_id');
	}
	function order_rma()
	{
        return $this->belongsTo('App\OrderCancel','order_meta_id','order_meta_id');
	}
}
