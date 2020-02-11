<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    protected $table="categories";
    protected $fillable = [
        'name', 'image','app_icon', 'description','status','slug','type','position','is_special'
    ];
    public function main_category()
    {
        return $this->hasMany('App\SubCategory',"category_id");
    }
    public function main_category_special()
    {
        return $this->hasMany('App\SubCategory',"category_id")->orderBy('id','desc')->limit(4);
    }
	public function product()
    {
        return $this->hasMany('App\Product',"category_id")->where('is_admin_approved',1);
    }
   
    //Added by Nadeem on 09.07.2019
    public static function getAllCat(){
        $cat = self::where('status','1')->get();
        if(!empty($cat)){
            return $cat;
        }else{
            return $cat = [];
        }
    }
    public static function getCatBySlug($categorySlug){
        $cat = self::where('slug', $categorySlug)->first();
        if(!empty($cat)){
            return $cat; 
        }else{
            return '';
        }
    }
    public static function getSubCat(){
        $subCat = self::with('main_category')->get();
        if(!empty($subCat)){
            return $subCat;
        }else{
            return [];
        }
    }
  
}
