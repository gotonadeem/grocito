<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class DeliveryBoyCommission extends Model
{
    protected $fillable = [
        'base_income',
        'per_km',
        'bonus',
    ];
	
    protected $table="delivery_boy_commissions";
}