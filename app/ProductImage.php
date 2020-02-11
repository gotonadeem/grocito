<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class ProductImage extends Model
{
    protected $fillable = [
        'product_id','image','watermark','in_stock','is_default'
    ];

    public function product_image()
    {
        return $this->belongsTo('App\Product','product_id');
    }
	public function product_rating_avg()
    {
          $result=$this->hasMany('App\ProductRating','item_id','id');
		  return $result;
    }
	
}
