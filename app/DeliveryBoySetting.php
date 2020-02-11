<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class DeliveryBoySetting extends Model
{
	protected $table="delivery_boy_commissions";
    protected $fillable = ['base_income','per_km','bonus','cod_limit'];
}
