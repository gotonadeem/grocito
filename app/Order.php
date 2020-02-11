<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Order extends Authenticatable
{
    protected $fillable = [
        'order_id','user_id','net_amount','distance','d_p_d_amount','cgst_amount','sgst_amount','shipped_by','status','payment_status','status_message','payment_amount','dock_no','reason','shipped_date','shipping_charge','seller_id','extra_amount','sender_id','payment_mode','address_id','margin_amount','wallet_amount','wallet_pay','wallet_amount','wallet_use','admin_commission','total_amount','courier_shipment_id','courier_order_id'
    ];
	
	 public function sender()
    {
        return $this->belongsTo('App\UserSender','sender_id');
    } 
	public function order_rma_details(){
        return $this->belongsTo('App\OrderRmaDetail','order_id','id');
    }
	public function address()
    {
        return $this->belongsTo('App\UserAddress','address_id');
    }

	public function sender_details()
    {
        return $this->belongsTo('App\UserSender','sender_id','id');
    } 
	
	public function address_details()
    {
        return $this->belongsTo('App\UserAddress','address_id','id');
    }
	
	public function user_kyc()
    {
        return $this->belongsTo('App\UserKyc','user_id','user_id');
    }
	public function product()
    {
        return $this->hasMany('App\OrderMeta',"order_id");
    }
	public function order_meta_data()
    {
        return $this->hasMany('App\OrderMeta');
    }
	
	public function order_meta()
    {
        return $this->belongsTo('App\OrderMeta')->where('status','shipped');
    }
	
	function seller()
	{
		 return $this->belongsTo('App\User','seller_id','id');
	}
	function seller_kyc()
	{
		 return $this->belongsTo('App\UserKyc','seller_id','user_id');
	}
	
	function user()
	{
		 return $this->belongsTo('App\User','user_id','id');
	}
	function reseller_payment()
	{
		 return $this->belongsTo('App\ResellerPayment','id','order_id');
	}
    function get_warehouse()
    {
        return $this->belongsTo('App\Warehouse','warehouse_id','id');
    }
	
}
