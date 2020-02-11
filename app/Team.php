<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Team extends Model
{
    protected $fillable = [
        'id',
        'name',
        'position',
        'description',
        'image',
    ];
    protected $table="our_team";
}
