<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class DeliveryTime extends Model
{
    protected $fillable = [
        'city_id',
        
        'time_interval',
        'start_time',
        'end_time',

		

    ];

	
   
}