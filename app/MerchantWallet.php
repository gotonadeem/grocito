<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class MerchantWallet extends Authenticatable
{
	protected $table="merchant_wallet_limit";
    protected $fillable = [
        'level','value','commission'
    ];
	
}
