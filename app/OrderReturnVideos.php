<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class OrderReturnVideos extends Authenticatable
{
	protected $table="order_return_videos";
    protected $fillable = [
        'order_id','product_id','video_name','order_meta_id',
    ];
	
	public function order_meta()
    {
        return $this->belongsTo('App\OrderMeta','order_id','order_id');
    }
	
	public function order_rma_details()
    {
        return $this->belongsTo('App\OrderRmaDetail','order_meta_id','order_meta_id');
    }
	public function order_exchanges()
    {
        return $this->belongsTo('App\OrderExchange','order_meta_id','order_meta_id');
    }
	
}
