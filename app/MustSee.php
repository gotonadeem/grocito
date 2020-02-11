<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class MustSee extends Authenticatable
{
    protected $fillable = [
        'language', 'title','status','link'
    ];
	protected $table="must_see";
}
