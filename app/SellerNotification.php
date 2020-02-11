<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class SellerNotification extends Authenticatable
{
    protected $fillable = ['seller_id', 'type','message','status'];
}
