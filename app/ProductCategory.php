<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class ProductCategory extends Model
{

    protected $fillable = [
        'product_id',
        'category_id'
    ];
	
    public function product_category()
    {
        return $this->belongsTo('App\Category','category_id');
    }
}

