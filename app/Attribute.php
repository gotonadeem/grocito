<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Attribute extends Authenticatable
{
    protected $fillable = ['type','name','value','code'];
	
	public function  cart_product()
	{
        return $this->belongsTo('App\Product','product_id');
	}
	public function  cart_image()
	{
        return $this->belongsTo('App\ProductImage','product_id','product_id');
	}

}