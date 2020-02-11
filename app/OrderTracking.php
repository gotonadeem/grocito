<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class OrderTracking extends Model
{
    protected $table="order_trackings";
    protected $fillable=['order_id','reason','date','type'];
}
?>
