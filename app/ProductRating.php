<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class ProductRating extends Model
{

    protected $fillable = [
        'product_id',
        'rating',
        'message',
        'item_id',
        'order_id',
        'user_id',
        'verify_status',
    ];
	
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
	
	public function product()
    {
        return $this->belongsTo('App\Product','product_id');
    }
	
}

