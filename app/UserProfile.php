<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class UserProfile extends Model
{
    protected $fillable = [
        'city', 'user_id',
    ];
      protected $table="user_profiles";
      public function user_profiles()
      {
        return $this->belongsTo('App\User');
      }
    
}
?>
