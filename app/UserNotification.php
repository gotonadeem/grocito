<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class UserNotification extends Model
{
    protected $fillable = [
        'id',
        'title',
        'image',
        'description'
		
    ];
    protected $table="user_notifications";
	
	
}
