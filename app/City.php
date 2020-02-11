<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class City extends Model
{
    protected $table="cities";
    public $timestamps = false;
    public function  state()	{
        return $this->belongsTo('App\State','state_id','id');
    }
    public function delivery_time(){
        return $this->hasOne('App\DeliveryTime','city_id','id');
    }
}

