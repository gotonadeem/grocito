<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class RaisingComplaint extends Model
{
    protected $fillable=['user_id','title','problem','solution','complaint_id','status'];
      public function user()
      {
        return $this->belongsTo('App\User');
      }

}
?>
