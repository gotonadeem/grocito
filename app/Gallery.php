<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Gallery extends Model
{
    protected $fillable = [
        'id',
        'images',
        'link',
        'status',
    ];
    protected $table="galleries";
	
	function main_category()
	{
		return $this->belongsTo('App\Category','link');
	}
}
