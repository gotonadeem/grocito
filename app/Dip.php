<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Dip extends Model
{
    protected $fillable = [
        'id',
        'images',
        'link',
        'status',
    ];
    protected $table="take_dips";
	
	function main_category()
	{
		return $this->belongsTo('App\Category','link');
	}
}
