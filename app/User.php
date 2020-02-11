<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
/*use Illuminate\Database\Eloquent\SoftDeletes;*/
class User extends Authenticatable
{
	
    protected $fillable = [
        'username', 'email','is_clear_notification_date','reff_code','city_name','ref_by','mobile','is_otp_varified','device_token','contact_details','password','role_id','otp','verify_status','banned','simple_pass','unique_code','category_id','agent_id','merchant_count','transaction_id'
    ];
    protected $hidden = [
        'remember_token',
    ];
    
      public function user_kyc()
     {
        return $this->hasOne('App\UserKyc');
     }

    public function user_name()
    {
        return $this->hasOne('App\Enquiry','user_id');
    }
  
     

   public function activation_wallet()
     {
        return $this->hasOne('App\ActivationWallet','user_id');
     }
    public function working_wallets()
    {
        return $this->hasOne('App\Deposit','user_id');
    }

     public function user_profile()
     {
        return $this->hasOne('App\UserProfile','user_id');
     }
    public function transfer()
    {
        return $this->hasOne('App\Transfer','sender');
    }
    public function working_wallet()
    {
        return $this->hasOne('App\WorkingWallet','id','user_id');
    }
	public function order()
    {
        return $this->hasOne('App\Order','seller_id');
    }


	
}
