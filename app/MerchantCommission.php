<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class MerchantCommission extends Authenticatable
{
	protected $table="merchant_commissions";
    protected $fillable = [
        'merchant_id','commission','level','level_amount','status','order_id'
    ];
	
	function user_wallet()
	{
	
        return $this->hasMany('App\Wallet',"merchant_id",'merchant_id');
	}
	
}
