<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Size extends Authenticatable
{
	protected $table="sizes";
    protected $fillable = [
        'name'
    ];
	
}
