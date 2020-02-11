<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Subscribe extends Authenticatable
{
	protected $table="subscribes";
    protected $fillable = [
        'email'
    ];
}
?>