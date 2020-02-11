<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class CategoryStockStatus extends Model
{
    protected $table="category_stock_status";
    protected $fillable = [
        'category_id', 'user_id'
    ];
    public function main_category()
    {
        return $this->hasOne('App\SubCategory',"category_id");
    }
}
