<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class ProductNote extends Model
{
    protected $table="product_notes";
    protected $fillable=['product_id','message','heading'];
	
	public function product()
    {
        return $this->belongsTo('App\Product',"product_id");
    }
}
?>
