<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Enquiry extends Model
{
    protected $fillable = [
        'property',
        'property_type',
        'user_id',
        'accommodation',
        'minBudget',
        'maxBudget',
        'sizeMin',
        'sizeType',
        'sizeMax',
        'projectStatus',
        'finance',
        'locationCity',
        'specialRequirement',
    ];
    protected $table="enquiries";

    function user_name()
    {
        return $this->belongsTo('App\User','id');
    }
}
