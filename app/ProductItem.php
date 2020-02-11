<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class ProductItem extends Model
{
    protected $fillable = [
        'product_id','seller_id','weight','price','sprice','qty','offer'
    ];

    public function scheme_product()
    {
        return $this->hasOne('App\SchemeProduct','product_item_id');
    }
    public function product()
    {
        return $this->belongsTo('App\Product','product_id');
    }
    public function product_image()
    {
        return $this->belongsTo('App\ProductImage','product_id','product_id');
    }
	public function product_rating_avg()
    {
          $result=$this->hasMany('App\ProductRating','item_id','id');
		  return $result;
    }
	
}
