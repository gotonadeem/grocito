<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class UserComplaint extends Model
{

    protected $fillable = ['user_id','complaint_id','subject','complaint_message','reply','status'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
?>