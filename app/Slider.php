<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Slider extends Model
{
    protected $fillable = [
        'id',
        'title',
        'images',
        'link',
		'type',
		'p_link',
		'link_type',
		'link_slug',
    ];
    protected $table="banners";
	
	function main_category()
	{
		return $this->belongsTo('App\Category','link');
	}
	function product()
	{
		return $this->belongsTo('App\Product','p_link');
	}
}
