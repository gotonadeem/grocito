<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Wishlist extends Model
{
    protected $table="wishlists";
    protected $fillable=['user_id','product_id','size'];
	public function product()
    {
        return $this->belongsTo('App\Product',"product_id");
    }
	
	public function product_image()
    {
        return $this->belongsTo('App\ProductImage',"product_id",'product_id');
    }
}
?>
