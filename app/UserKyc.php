<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class UserKyc extends Model
{
    protected $table="user_kyc";
    protected $fillable=['user_id','food_license_no','alternate_mobile_no','bank_name','driving_licence_image','f_name','l_name','dob','country_id','state_id','city_id','profile_image',
	'pan_number','tan_number','aadhar_number','gst_number','pincode','seller_image','alternate_mobile_no','cancel_cheque',
	'account_number','pan_image','is_verified','address_1','address_2','aadhar_image','cin_image','cin_number','signature',
	'account_holder_name','ifsc_code','warehouse_id','subadmin_id','cartlay_commission','business_name','business_address','delivery_pincode','longitude','latitude'];
      public function user()
      {
        return $this->belongsTo('App\User');
      }
	  
	  function country()
	  {
		  return $this->belongsTo('App\Country');
	  }
	  function state()
	  {
		  return $this->belongsTo('App\State');
	  }
	  function city()
	  {
		  return $this->belongsTo('App\City');
	  }
	
	public function warehouse()
    {
        return $this->belongsTo('App\Warehouse','warehouse_id');
    }
}
?>
