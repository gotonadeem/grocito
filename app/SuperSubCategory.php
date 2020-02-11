<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class SuperSubCategory extends Model
{
    protected $table="super_sub_categories";
    protected $fillable = ['name','category_id','image', 'description','sub_category_id'];
    public function sub_category()
    {
        return $this->belongsTo('App\SubCategory','sub_category_id');
    }
    public function main_category()
    {
        return $this->belongsTo('App\Category','category_id');
    }
}
