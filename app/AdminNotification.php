<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class AdminNotification extends Authenticatable
{
    protected $fillable = ['seller_id', 'type','message','status'];
}
