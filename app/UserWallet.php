<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class UserWallet extends Model
{
	  protected $fillable = ['user_id','amount','type','status'];
      protected $table="user_wallets";
      
	  public function user()
      {
        return $this->belongsTo('App\User');
      }

	  public function user_kyc()
      {
        return $this->belongsTo('App\UserKyc','user_id','user_id');
      }
    
}