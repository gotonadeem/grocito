<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Delivery extends Model
{
    protected $table="delivery_charges";
	protected $fillable = [
        'type',
        'city_id',
        'radius',
        'radius_charge',
        'out_of_radius_charge',
        'min_order',
    ];
}