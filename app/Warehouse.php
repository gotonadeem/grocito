<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
/*use Illuminate\Database\Eloquent\SoftDeletes;*/
class Warehouse extends Model
{
    protected $fillable = ['city_id','name','address', 'lattitude','longitude','warehouse_pincode','pincode','status'];
    protected $hidden = [
        'remember_token',
    ];
    public function get_city()
    {
        return $this->belongsTo('App\City','city_id');
    }
}
