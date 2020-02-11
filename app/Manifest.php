<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Manifest extends Model
{
    protected $table="manifests";
    protected $fillable = [
        'seller_id', 'order_id', 'service'
    ];
   
    public function seller()
    {
        return $this->belongsTo('App\User',"seller_id");
    }
   
}
