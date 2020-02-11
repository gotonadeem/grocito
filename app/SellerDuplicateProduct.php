<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class SellerDuplicateProduct extends Model
{
    protected $fillable = [
        'seller_id',
        'product_id',
    ];
    public function product(){
        return $this->belongsTo('App\Product');
    }
   public function get_seller(){
       return $this->belongsTo('App\User','seller_id','id');
   }
}

