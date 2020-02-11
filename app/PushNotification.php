<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class PushNotification extends Authenticatable
{
    protected $fillable = [
        'title', 'message','status','user_id',
    ];
}
