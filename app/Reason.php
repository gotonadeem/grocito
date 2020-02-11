<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Reason extends Model
{
    protected $fillable = [
        'id',
        'title',
    ];
    protected $table="reasons";
}
