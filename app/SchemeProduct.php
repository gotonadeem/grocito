<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class SchemeProduct extends Model
{
    protected $fillable = [
        'user_id',
        'cat_id',
        'sub_cat_id',
        'product_id',
        'product_item_id',
        'offer_name',
        'image',
        'status',

    ];
    public function get_product()
    {
        return $this->belongsTo('App\Product','product_id');
    }
    public function get_product_item()
    {
        return $this->belongsTo('App\ProductItem','product_item_id');
    }
	
}
