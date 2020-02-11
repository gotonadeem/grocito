<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class PropertyAmenity extends Model
{
    protected $fillable = [
        'property_id',
        'amenity_id',
    ];
}
