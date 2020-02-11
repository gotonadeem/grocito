<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class SponsorPlan extends Model
{
    protected $fillable = [
        'plan_details',
        'price',
	    ];
}