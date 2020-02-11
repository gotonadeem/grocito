<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Coupon extends Model
{

    protected $table="coupons";
	protected $fillable = [
        'code',
        'start_date',
        'end_date',
        'discount_amount',
        'discount_unit',
        'no_of_usage',
        'usage_per_user',
        'status',
        'min_ord_amount',
        'entry_date',
    ];
}