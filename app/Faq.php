<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Faq extends Model
{
    protected $fillable = [
        'id',
        'title',
        'description',
		'section_id'
    ];
	
    protected $table="faq";

	 function question()
	 {
		  return $this->hasMany('App\Question','faq_id');
	 }
}