<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Cms extends Model
{
    protected $table="cms";
	protected $fillable = [
        'name',
        'description',
        'status',
    ];
}



