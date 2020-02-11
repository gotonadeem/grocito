<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Testimonial extends Authenticatable
{
    protected $fillable = [
        'name', 'address','description','image',
    ];
}
