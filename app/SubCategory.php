<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class SubCategory extends Model
{

    protected $table="sub_categories";
    protected $fillable = ['name','category_id','image', 'description','status','slug','category_slug'];
    public function main_category()
    {
        return $this->belongsTo('App\Category','category_id');
    }

    public function super_sub_category()
    {
        return $this->hasOne('App\SuperSubCategory','sub_category_id');
    }

    public function super_sub_cat()
    {
        return $this->hasMany('App\SuperSubCategory','sub_category_id');
    }
    public static function getSubCatBySlug($subCatSlug){
        $cat = self::where('slug', $subCatSlug)->first();
        if(!empty($cat)){
            return $cat;
        }else{
            return '';
        }
    }

}

