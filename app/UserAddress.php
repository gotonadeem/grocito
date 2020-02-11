<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class UserAddress extends Model
{
      protected $fillable = ['city', 'user_id','name','mobile','house','street','landmark','state','pincode','is_default','type','address','lattitude','longitude'];
      protected $table="user_addresses";
      
	  
	  public function user()
      {
        return $this->belongsTo('App\User');
      }
    
}
?>
