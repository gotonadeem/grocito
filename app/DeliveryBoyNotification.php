<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class DeliveryBoyNotification extends Model
{
    protected $table="delivery_boy_notifications";
    protected $fillable=['order_id','warehouse_id','distance','seller_id','type','status',
	'delivery_boy_id','user_id','job_id'];
    public function get_delivery_boy()
    {
        return $this->belongsTo('App\User','delivery_boy_id');
    }
}
?>