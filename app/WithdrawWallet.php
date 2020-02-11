<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class WithdrawWallet extends Model
{
	  protected $fillable = ['user_id','order_id','status','amount','transaction_status'];
      protected $table="withdrawal_wallet";
      
	  public function user()
      {
        return $this->belongsTo('App\User');
      }

	  public function user_kyc()
      {
        return $this->belongsTo('App\UserKyc','user_id','user_id');
      }
    
}