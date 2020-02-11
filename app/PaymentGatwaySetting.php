<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class PaymentGatwaySetting extends Model
{
    protected $table="payment_gatway_settings";
    protected $fillable = ['merchant_key','salt'];
}
?>
