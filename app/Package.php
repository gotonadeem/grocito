<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Package extends Model
{
    protected $fillable = [
        'package_name',
        'invested_amount_from',
        'invested_amount_to',
        'daily_roi',
        'referral_income',
        'reword_bonus',
        'days_on_roi'
    ];
    protected $table="packages";
}
