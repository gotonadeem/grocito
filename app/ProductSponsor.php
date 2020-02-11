<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class ProductSponsor extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'sponsor_plan_id',
		'date',
		'price',
        'admin_status',
        'created_at',
    ];

	public function product()
    {
        return $this->hasMany('App\Product',"product_id");
    }
	
	public function product_data()
    {
        return $this->belongsTo('App\Product','product_id');
    }
	
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    } 
	
	public function plan()
    {
        return $this->belongsTo('App\SponsorPlan','sponsor_plan_id');
    }
}
