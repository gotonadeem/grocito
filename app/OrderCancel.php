<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class OrderCancel extends Authenticatable
{
	protected $table="order_rma";
	
    protected $fillable = [
        'order_meta_id','order_id','reason','comment',
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
	

}
