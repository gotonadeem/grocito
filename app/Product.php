<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    protected $fillable = [
        'name',
        'city_id',
        'user_id',
        'sku',
		'image',
		'p_gst',
		'color',
        'description',
        'type',
        'status',
		'category_id',
		'sub_category_id',
		'sub_category_slug',
		'super_sub_category_id',
		'super_sub_category_slug',
		'stock_status',
		'share_count',
		'is_return',
		'is_exchange',
		'is_cod',
		'shipping_free_amount',
        'related_product',
        'slug',
        'sku',
        'category_slug',
        'is_featured',
		'saleplus_commission',
		'brand_id',
        'is_admin_approved'
		
    ];
    public function product_item(){
		
        return $this->hasMany('App\ProductItem');
    }
    public function main_category()
    {
        return $this->belongsTo('App\Category','category_id');
    }
    public function sub_category()
    {
        return $this->belongsTo('App\SubCategory','sub_category_id')->whereNotNull('id');
    }
    public function city()
    {
        return $this->belongsTo('App\City',"city_id");
    }
    public function brand()
    {
        return $this->belongsTo('App\Brand',"brand_id");
    }
    public function super_sub_category()
    {
        return $this->belongsTo('App\SuperSubCategory','super_sub_category_id');
    }
	public function product_image()
    {
        return $this->hasMany('App\ProductImage',"product_id");
    }
	
	public function product_sponsor()
    {
		$date=date('Y-m-d'); //"FIND_IN_SET('$date',date)"
        return $this->hasOne('App\ProductSponsor',"product_id")->where("FIND_IN_SET($date,date)");
    }
	
	public function product_category()
    {
        return $this->hasMany('App\ProductCategory',"product_id");
    }
	public function user_name()
    {
        return $this->belongsTo('App\User',"user_id");
    }
	public function user_name_status()
    {
        return $this->belongsTo('App\User',"user_id")->where('banned',0);
    }
	public function product_rating()
    {
        return $this->hasOne('App\ProductRating',"product_id",'id');
    }
	public function product_note()
    {
        return $this->hasMany('App\ProductNote',"product_id");
    }
	
	
}
