<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
/*use Illuminate\Database\Eloquent\SoftDeletes;*/
class Report extends Authenticatable
{
    protected $fillable = [
        'user_id',
        'income',
        'level',
        'type'

    ];
    protected $table="working_wallets";

    /*public function user()
    {
        return $this->hasOne('App\User');
    }*/
    public function working_wallet()
    {
        return $this->belongsTo('App\Deposit');
    }
}
