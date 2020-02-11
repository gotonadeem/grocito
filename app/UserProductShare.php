<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class UserProductShare extends Authenticatable
{
	protected $table="user_product_shares";
    protected $fillable = [
        'user_id','product_id',
    ];
	
	 public function product()
    {
        return $this->belongsTo('App\Product','product_id','id')->where('stock_status',1);
    }
}
