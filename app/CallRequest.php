<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class CallRequest extends Model
{
    protected $fillable=['user_id','status'];
      public function user()
      {
        return $this->belongsTo('App\User');
      }
	  
}
?>
