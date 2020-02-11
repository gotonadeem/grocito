<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Question extends Model
{
    protected $fillable = [
        'id',
        'faq_id',
        'title',
        'description',
        'status',
    ];
    protected $table="questions";
	
	public function faq()
    {
        return $this->belongsTo('App\Faq','faq_id');
    }
}
