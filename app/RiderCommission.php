<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class RiderCommission extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'type',
    ];
	
    protected $table="rider_commissions";
	public function order()
	{
		 return $this->belongsTo('App\Order','order_id','id');
	}
}