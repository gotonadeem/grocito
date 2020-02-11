<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class DeliveryBoyRide extends Model
{
    protected $table="delivery_boy_rides";
    protected $fillable=['delivery_boy_id','order_id','seller_id','user_id',
	'sub_order_id','distance','amount_per_km','bonus','warehouse_id','date','from_address','to_address','type','payment_mode','job_id'];
	
	public function product()
    {
        return $this->belongsTo('App\Product',"product_id");
    }
	
	public function order()
    {
        return $this->belongsTo('App\Order',"order_id");
    }
	public function user()
    {
        return $this->belongsTo('App\User',"delivery_boy_id",'id');
    }
	public function user_kyc()
    {
        return $this->belongsTo('App\UserKyc',"delivery_boy_id",'user_id');
    }
	public function seller()
    {
        return $this->belongsTo('App\User',"seller_id",'id');
    }
	public function warehouse()
    {
        return $this->belongsTo('App\Warehouse',"warehouse_id",'id');
    }
	
	
	
}
?>
