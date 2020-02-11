<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class PaymentSlot extends Model
{
	protected $table="payment_slots";	
    protected $fillable = [
        'from_date',
        'to_date',
    ];
}