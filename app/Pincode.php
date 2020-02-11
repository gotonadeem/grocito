<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Pincode extends Model
{
    protected $fillable = ['city_id', 'pincode', 'address','status'];

    public function city(){

        return $this->hasOne('App\City','id','city_id');
    }
   
    
}
?>
