<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Cart extends Authenticatable
{
    protected $fillable = ['product_id','item_id','gst_percentage','attributes','qty','admin_commission','is_shipping_free','is_exchange','is_return','shipping_free_amount','user_id','weight','price','sprice','size','seller_id','product_image','product_name','created_at','system_address'
    ];
	
	public function  cart_product()
	{
        return $this->belongsTo('App\Product','product_id');
	}
	public function  cart_image()
	{
        return $this->belongsTo('App\ProductImage','product_id','product_id');
	}
	public function  get_item()
	{
		return $this->belongsTo('App\ProductItem','item_id');
	}
}