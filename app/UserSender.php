<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class UserSender extends Model
{
	  protected $fillable = ['user_id','name','mobile','is_default'];
      protected $table="user_senders";
      
	  public function user()
      {
        return $this->belongsTo('App\User');
      }
    
}