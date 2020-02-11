<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class SubadminAccess extends Model
{
    protected $fillable = [
        'access_permission', 'user_id','action'
    ];
    /**
     * The attributes that should be hidden for arrays.
         *
     * @var array
     */
	protected $table="subadmin_access"; 
   
}
