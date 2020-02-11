<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Section extends Model
{
    protected $fillable = [
        'id',
        'section_id',
        'title',
        'image',
        'description',
        'status',
    ];
    protected $table="sections";
}
